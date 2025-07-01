<?php

namespace App\Services;

interface PaymentProcessor
{
    public function processPayment(float $amount, array $paymentDetails): array;
}