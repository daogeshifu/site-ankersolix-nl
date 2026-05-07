<?php

namespace App\Services\Payment;

use App\Models\Order;
use Illuminate\Http\Request;

interface PaymentProviderInterface
{
    /**
     * Create payment order and return redirect URL
     *
     * @param Order $order
     * @param array $params Additional parameters (plan info, etc.)
     * @return array ['url' => string, 'provider_order_id' => string|null]
     */
    public function createOrder(Order $order, array $params): array;

    /**
     * Handle synchronous callback (return URL)
     *
     * @param Request $request
     * @return array ['order_no' => string, 'success' => bool, 'provider_data' => array]
     */
    public function handleCallback(Request $request): array;

    /**
     * Handle asynchronous notification (webhook/notify)
     *
     * @param Request $request
     * @return array ['order_no' => string, 'success' => bool, 'provider_data' => array]
     */
    public function handleNotify(Request $request): array;

    /**
     * Process refund
     *
     * @param Order $order
     * @param float $amount
     * @return array ['success' => bool, 'refund_id' => string|null, 'message' => string]
     */
    public function refund(Order $order, float $amount): array;
}
