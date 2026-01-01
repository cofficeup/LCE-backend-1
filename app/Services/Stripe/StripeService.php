<?php

namespace App\Services\Stripe;

use App\Models\User;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\StripeCustomer;
use Stripe\StripeClient;
use Stripe\Exception\ApiErrorException;

class StripeService
{
    protected StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('stripe.secret'));
    }

    /**
     * Create or retrieve existing Stripe customer for user.
     */
    public function createOrGetCustomer(User $user): string
    {
        // Check if user already has a Stripe customer
        $existing = StripeCustomer::where('user_id', $user->id)->first();

        if ($existing) {
            return $existing->stripe_customer_id;
        }

        // Create new Stripe customer
        try {
            $customer = $this->stripe->customers->create([
                'email' => $user->email,
                'name' => $user->first_name . ' ' . $user->last_name,
                'metadata' => [
                    'user_id' => $user->id,
                ],
            ]);

            // Store the mapping
            StripeCustomer::create([
                'user_id' => $user->id,
                'stripe_customer_id' => $customer->id,
            ]);

            return $customer->id;
        } catch (ApiErrorException $e) {
            throw new \RuntimeException('Failed to create Stripe customer: ' . $e->getMessage());
        }
    }

    /**
     * Create a Payment Intent for an invoice.
     */
    public function createPaymentIntent(Invoice $invoice, User $user): array
    {
        // Ensure customer exists
        $customerId = $this->createOrGetCustomer($user);

        // Amount in cents
        $amountCents = (int) round($invoice->total * 100);

        if ($amountCents < 50) {
            throw new \InvalidArgumentException('Payment amount must be at least $0.50');
        }

        try {
            $paymentIntent = $this->stripe->paymentIntents->create([
                'amount' => $amountCents,
                'currency' => strtolower($invoice->currency ?? config('stripe.currency')),
                'customer' => $customerId,
                'metadata' => [
                    'invoice_id' => $invoice->id,
                    'user_id' => $user->id,
                    'invoice_type' => $invoice->type,
                ],
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            return [
                'payment_intent_id' => $paymentIntent->id,
                'client_secret' => $paymentIntent->client_secret,
                'amount' => $invoice->total,
                'currency' => $invoice->currency ?? 'USD',
            ];
        } catch (ApiErrorException $e) {
            throw new \RuntimeException('Failed to create payment intent: ' . $e->getMessage());
        }
    }

    /**
     * Retrieve a Payment Intent by ID.
     */
    public function getPaymentIntent(string $paymentIntentId): \Stripe\PaymentIntent
    {
        try {
            return $this->stripe->paymentIntents->retrieve($paymentIntentId);
        } catch (ApiErrorException $e) {
            throw new \RuntimeException('Failed to retrieve payment intent: ' . $e->getMessage());
        }
    }

    /**
     * Issue a refund for a payment.
     */
    public function refundPayment(Payment $payment, ?int $amountCents = null): \Stripe\Refund
    {
        if (!$payment->stripe_payment_intent_id) {
            throw new \InvalidArgumentException('Payment has no Stripe payment intent');
        }

        try {
            $params = [
                'payment_intent' => $payment->stripe_payment_intent_id,
            ];

            // Partial refund if amount specified
            if ($amountCents !== null) {
                $params['amount'] = $amountCents;
            }

            return $this->stripe->refunds->create($params);
        } catch (ApiErrorException $e) {
            throw new \RuntimeException('Failed to process refund: ' . $e->getMessage());
        }
    }

    /**
     * Verify webhook signature.
     */
    public function constructWebhookEvent(string $payload, string $signature): \Stripe\Event
    {
        $webhookSecret = config('stripe.webhook_secret');

        if (!$webhookSecret) {
            throw new \RuntimeException('Stripe webhook secret not configured');
        }

        try {
            return \Stripe\Webhook::constructEvent(
                $payload,
                $signature,
                $webhookSecret
            );
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            throw new \RuntimeException('Invalid webhook signature');
        }
    }
}
