<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Charge;

class StripePaymentProcessor implements PaymentProcessor
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function processPayment(float $amount, array $paymentDetails): array
    {
        try {
            $amountInCents = (int)round($amount * 100);
            $charge = Charge::create([
                'amount' => $amountInCents,
                'currency' => 'brl',
                'source' => $paymentDetails['token'],
                'description' => 'Pagamento para evento ' . $paymentDetails['event_id'],
            ]);

            return [
                'status' => 'success',
                'transaction_id' => $charge->id,
                'message' => 'Pagamento aprovado!'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'failed',
                'transaction_id' => null,
                'message' => 'Pagamento recusado: ' . $e->getMessage()
            ];
        }
    }
}