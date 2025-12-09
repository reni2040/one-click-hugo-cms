<?php

namespace App\Services;

use App\Models\Payment;
use Razorpay\Api\Api;

class PaymentService
{
    public function __construct(private Payment $payments, private array $config)
    {
    }

    public function createOrder(int $amountPaise, string $receipt): array
    {
        $api = new Api($this->config['razorpay_key'], $this->config['razorpay_secret']);
        return $api->order->create([
            'amount' => $amountPaise,
            'currency' => 'INR',
            'receipt' => $receipt,
        ]);
    }

    public function store(array $data): int
    {
        return $this->payments->create($data);
    }
}
