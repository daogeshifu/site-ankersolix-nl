<?php

namespace App\Services\Payment;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalPaymentProvider implements PaymentProviderInterface
{
    private PayPalClient $client;

    public function __construct()
    {
        $this->client = new PayPalClient;
        $this->client->setApiCredentials(config('paypal'));
        $this->client->getAccessToken();
    }

    public function createOrder(Order $order, array $params): array
    {
        $description = ($params['plan_name'] ?? $order->plan_code) . ' - ' .
                       ($order->billing_cycle === 'annual' ? 'Annual' : 'Monthly');

        $orderData = [
            'intent' => 'CAPTURE',
            'application_context' => [
                'return_url' => route('payment.paypal.success'),
                'cancel_url' => route('payment.failed'),
                'brand_name' => config('app.name'),
                'locale' => 'en-US',
                'landing_page' => 'BILLING',
                'shipping_preference' => 'NO_SHIPPING',
                'user_action' => 'PAY_NOW',
            ],
            'purchase_units' => [
                [
                    'reference_id' => $order->order_no,
                    'description' => $description,
                    'custom_id' => 'CUST-' . $order->order_no,
                    'soft_descriptor' => config('app.name'),
                    'amount' => [
                        'currency_code' => $order->currency,
                        'value' => number_format($order->amount, 2, '.', ''),
                        'breakdown' => [
                            'item_total' => [
                                'currency_code' => $order->currency,
                                'value' => number_format($order->amount, 2, '.', ''),
                            ],
                        ],
                    ],
                    'items' => [
                        [
                            'name' => $description,
                            'description' => $description,
                            'quantity' => '1',
                            'unit_amount' => [
                                'currency_code' => $order->currency,
                                'value' => number_format($order->amount, 2, '.', ''),
                            ],
                            'category' => 'DIGITAL_GOODS',
                        ],
                    ],
                ],
            ],
        ];

        $paypalOrder = $this->client->createOrder($orderData);

        if (!isset($paypalOrder['status']) || $paypalOrder['status'] !== 'CREATED') {
            Log::error('PayPal create order failed', ['response' => $paypalOrder]);
            throw new \Exception('Failed to create PayPal order');
        }

        $approveUrl = '';
        foreach ($paypalOrder['links'] as $link) {
            if ($link['rel'] === 'approve') {
                $approveUrl = $link['href'];
                break;
            }
        }

        if (!$approveUrl) {
            throw new \Exception('PayPal approval link not found');
        }

        return [
            'url' => $approveUrl,
            'provider_order_id' => $paypalOrder['id'],
        ];
    }

    public function handleCallback(Request $request): array
    {
        $token = $request->input('token');

        if (!$token) {
            return ['order_no' => null, 'success' => false, 'provider_data' => []];
        }

        try {
            $result = $this->client->capturePaymentOrder($token);

            if (isset($result['status']) && $result['status'] === 'COMPLETED') {
                $orderNo = $result['purchase_units'][0]['reference_id'] ?? null;
                $transactionId = $result['purchase_units'][0]['payments']['captures'][0]['id'] ?? null;

                return [
                    'order_no' => $orderNo,
                    'success' => true,
                    'provider_data' => [
                        'provider_order_id' => $token,
                        'provider_transaction_id' => $transactionId,
                        'response' => $result,
                    ],
                ];
            }

            return [
                'order_no' => null,
                'success' => false,
                'provider_data' => ['response' => $result],
            ];
        } catch (\Exception $e) {
            Log::error('PayPal capture failed: ' . $e->getMessage());
            return ['order_no' => null, 'success' => false, 'provider_data' => []];
        }
    }

    public function handleNotify(Request $request): array
    {
        $payload = $request->all();
        $eventType = $payload['event_type'] ?? '';

        Log::info('PayPal webhook received', ['event_type' => $eventType]);

        if ($eventType === 'PAYMENT.CAPTURE.COMPLETED') {
            $resource = $payload['resource'] ?? [];
            $customId = $resource['custom_id'] ?? '';
            $orderNo = str_replace('CUST-', '', $customId);

            return [
                'order_no' => $orderNo,
                'success' => true,
                'provider_data' => [
                    'provider_transaction_id' => $resource['id'] ?? null,
                    'response' => $payload,
                ],
            ];
        }

        return ['order_no' => null, 'success' => false, 'provider_data' => $payload];
    }

    public function refund(Order $order, float $amount): array
    {
        try {
            if (!$order->provider_transaction_id) {
                return ['success' => false, 'refund_id' => null, 'message' => 'No transaction ID for refund'];
            }

            $result = $this->client->refundCapturedPayment(
                $order->provider_transaction_id,
                number_format($amount, 2, '.', ''),
                'Refund for order ' . $order->order_no
            );

            if (isset($result['status']) && $result['status'] === 'COMPLETED') {
                return [
                    'success' => true,
                    'refund_id' => $result['id'] ?? null,
                    'message' => 'Refund successful',
                ];
            }

            return ['success' => false, 'refund_id' => null, 'message' => json_encode($result)];
        } catch (\Exception $e) {
            Log::error('PayPal refund failed: ' . $e->getMessage());
            return ['success' => false, 'refund_id' => null, 'message' => $e->getMessage()];
        }
    }
}
