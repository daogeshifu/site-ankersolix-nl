<?php

namespace App\Services\Payment;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yansongda\Pay\Pay;

class AlipayPaymentProvider implements PaymentProviderInterface
{
    public function createOrder(Order $order, array $params): array
    {
        $subject = ($params['plan_name'] ?? $order->plan_code) . ' - ' .
                   ($order->billing_cycle === 'annual' ? 'Annual' : 'Monthly');

        $alipayOrder = [
            'out_trade_no' => $order->order_no,
            'total_amount' => number_format($order->amount, 2, '.', ''),
            'subject' => $subject,
            'product_code' => 'FAST_INSTANT_TRADE_PAY',
        ];

        // If auto_renew and has agreement signing params, add sign parameters
        if (!empty($params['auto_renew'])) {
            $alipayOrder['agreement_sign_params'] = [
                'personal_product_code' => 'CYCLE_PAY_AUTH_P',
                'sign_scene' => 'INDUSTRY|DIGITAL_MEDIA',
                'access_params' => [
                    'channel' => 'ALIPAYAPP',
                ],
                'period_rule_params' => [
                    'period_type' => $order->billing_cycle === 'annual' ? 'YEAR' : 'MONTH',
                    'period' => 1,
                    'execute_time' => now()->format('Y-m-d'),
                    'single_amount' => number_format($order->amount, 2, '.', ''),
                    'total_amount' => number_format($order->amount * 12, 2, '.', ''),
                    'total_payments' => 0, // unlimited
                ],
            ];
        }

        try {
            $result = Pay::alipay()->web($alipayOrder);

            // For web payment, result is an HTTP response with form auto-submit
            // We return the response body as a renderable HTML
            return [
                'url' => null, // Alipay web returns form HTML, not a URL
                'provider_order_id' => $order->order_no,
                'html' => $result->getBody()->getContents(),
            ];
        } catch (\Exception $e) {
            Log::error('Alipay create order failed: ' . $e->getMessage());
            throw new \Exception('Failed to create Alipay order: ' . $e->getMessage());
        }
    }

    public function handleCallback(Request $request): array
    {
        try {
            $data = Pay::alipay()->callback($request->all());

            $tradeStatus = $data['trade_status'] ?? '';
            $orderNo = $data['out_trade_no'] ?? null;

            if (in_array($tradeStatus, ['TRADE_SUCCESS', 'TRADE_FINISHED'])) {
                return [
                    'order_no' => $orderNo,
                    'success' => true,
                    'provider_data' => [
                        'provider_order_id' => $data['trade_no'] ?? null,
                        'provider_transaction_id' => $data['trade_no'] ?? null,
                        'response' => (array) $data,
                    ],
                ];
            }

            return [
                'order_no' => $orderNo,
                'success' => false,
                'provider_data' => ['response' => (array) $data],
            ];
        } catch (\Exception $e) {
            Log::error('Alipay callback verification failed: ' . $e->getMessage());
            return ['order_no' => null, 'success' => false, 'provider_data' => []];
        }
    }

    public function handleNotify(Request $request): array
    {
        try {
            $data = Pay::alipay()->callback($request->all());

            $tradeStatus = $data['trade_status'] ?? '';
            $orderNo = $data['out_trade_no'] ?? null;

            Log::info('Alipay notify received', ['trade_status' => $tradeStatus, 'order_no' => $orderNo]);

            if (in_array($tradeStatus, ['TRADE_SUCCESS', 'TRADE_FINISHED'])) {
                return [
                    'order_no' => $orderNo,
                    'success' => true,
                    'provider_data' => [
                        'provider_order_id' => $data['trade_no'] ?? null,
                        'provider_transaction_id' => $data['trade_no'] ?? null,
                        'agreement_no' => $data['agreement_no'] ?? null,
                        'response' => (array) $data,
                    ],
                ];
            }

            return [
                'order_no' => $orderNo,
                'success' => false,
                'provider_data' => ['response' => (array) $data],
            ];
        } catch (\Exception $e) {
            Log::error('Alipay notify verification failed: ' . $e->getMessage());
            return ['order_no' => null, 'success' => false, 'provider_data' => []];
        }
    }

    public function refund(Order $order, float $amount): array
    {
        try {
            $result = Pay::alipay()->refund([
                'out_trade_no' => $order->order_no,
                'refund_amount' => number_format($amount, 2, '.', ''),
                'out_request_no' => $order->order_no . '_refund_' . time(),
            ]);

            $resultData = (array) $result;

            if (($resultData['code'] ?? '') === '10000') {
                return [
                    'success' => true,
                    'refund_id' => $resultData['trade_no'] ?? null,
                    'message' => 'Refund successful',
                ];
            }

            return [
                'success' => false,
                'refund_id' => null,
                'message' => $resultData['sub_msg'] ?? 'Refund failed',
            ];
        } catch (\Exception $e) {
            Log::error('Alipay refund failed: ' . $e->getMessage());
            return ['success' => false, 'refund_id' => null, 'message' => $e->getMessage()];
        }
    }
}
