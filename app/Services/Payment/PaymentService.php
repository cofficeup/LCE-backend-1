<?php

namespace App\Services\Payment;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\User;
use App\Models\AuditLog;
use App\Services\Stripe\StripeService;
use App\Services\Invoice\InvoiceService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    protected StripeService $stripe;
    protected InvoiceService $invoices;

    public function __construct(StripeService $stripe, InvoiceService $invoices)
    {
        $this->stripe = $stripe;
        $this->invoices = $invoices;
    }

    /**
     * Create a payment intent for an invoice.
     * Returns client_secret for frontend.
     */
    public function createPaymentIntent(Invoice $invoice, User $user): array
    {
        // Validate invoice state
        if (!in_array($invoice->status, ['draft', 'pending_payment'])) {
            throw new \DomainException('Invoice is not payable. Current status: ' . $invoice->status);
        }

        // Check for existing pending payment
        $existingPayment = Payment::where('invoice_id', $invoice->id)
            ->where('status', Payment::STATUS_PENDING)
            ->first();

        if ($existingPayment && $existingPayment->stripe_payment_intent_id) {
            // Return existing payment intent
            $intent = $this->stripe->getPaymentIntent($existingPayment->stripe_payment_intent_id);
            return [
                'payment_intent_id' => $intent->id,
                'client_secret' => $intent->client_secret,
                'amount' => $invoice->total,
                'currency' => $invoice->currency ?? 'USD',
                'payment_id' => $existingPayment->id,
            ];
        }

        return DB::transaction(function () use ($invoice, $user) {
            // Mark invoice as pending payment
            if ($invoice->status === 'draft') {
                $this->invoices->markPendingPayment($invoice);
            }

            // Create Stripe payment intent
            $intentData = $this->stripe->createPaymentIntent($invoice, $user);

            // Create payment record
            $payment = Payment::create([
                'invoice_id' => $invoice->id,
                'user_id' => $user->id,
                'amount' => $invoice->total,
                'currency' => $invoice->currency ?? 'USD',
                'status' => Payment::STATUS_PENDING,
                'stripe_payment_intent_id' => $intentData['payment_intent_id'],
            ]);

            return array_merge($intentData, [
                'payment_id' => $payment->id,
            ]);
        });
    }

    /**
     * Handle successful payment (from webhook).
     */
    public function markPaymentSucceeded(string $paymentIntentId): void
    {
        $payment = Payment::where('stripe_payment_intent_id', $paymentIntentId)->first();

        if (!$payment) {
            Log::warning('Payment not found for intent: ' . $paymentIntentId);
            return;
        }

        // Idempotency: skip if already succeeded
        if ($payment->isSucceeded()) {
            Log::info('Payment already succeeded: ' . $paymentIntentId);
            return;
        }

        DB::transaction(function () use ($payment) {
            // Update payment
            $payment->update([
                'status' => Payment::STATUS_SUCCEEDED,
                'paid_at' => now(),
            ]);

            // Update invoice
            $invoice = $payment->invoice;
            if ($invoice && $invoice->status !== 'paid') {
                $this->invoices->markPaid($invoice, [
                    'payment_id' => $payment->id,
                    'stripe_payment_intent_id' => $payment->stripe_payment_intent_id,
                ]);
            }

            Log::info('Payment succeeded: ' . $payment->stripe_payment_intent_id);
        });
    }

    /**
     * Handle failed payment (from webhook).
     */
    public function markPaymentFailed(string $paymentIntentId, ?string $reason = null): void
    {
        $payment = Payment::where('stripe_payment_intent_id', $paymentIntentId)->first();

        if (!$payment) {
            Log::warning('Payment not found for intent: ' . $paymentIntentId);
            return;
        }

        // Idempotency: skip if already failed
        if ($payment->status === Payment::STATUS_FAILED) {
            return;
        }

        $payment->update([
            'status' => Payment::STATUS_FAILED,
            'failure_reason' => $reason,
        ]);

        Log::info('Payment failed: ' . $paymentIntentId . ' - ' . $reason);
    }

    /**
     * Process refund for an invoice.
     */
    public function processRefund(Invoice $invoice, string $reason, ?User $admin = null): Payment
    {
        // Find successful payment
        $payment = Payment::where('invoice_id', $invoice->id)
            ->where('status', Payment::STATUS_SUCCEEDED)
            ->first();

        if (!$payment) {
            throw new \DomainException('No successful payment found for this invoice');
        }

        if (!$payment->stripe_payment_intent_id) {
            throw new \DomainException('Payment has no Stripe payment intent');
        }

        return DB::transaction(function () use ($invoice, $payment, $reason, $admin) {
            // Issue Stripe refund
            $refund = $this->stripe->refundPayment($payment);

            // Update payment
            $payment->update([
                'status' => Payment::STATUS_REFUNDED,
                'stripe_refund_id' => $refund->id,
                'refunded_at' => now(),
            ]);

            // Update invoice
            $this->invoices->markRefunded($invoice, $reason);

            // Create audit log
            AuditLog::create([
                'user_id' => $admin?->id,
                'action' => 'invoice_refund',
                'entity_type' => 'invoice',
                'entity_id' => $invoice->id,
                'metadata' => [
                    'reason' => $reason,
                    'payment_id' => $payment->id,
                    'stripe_refund_id' => $refund->id,
                    'amount' => $payment->amount,
                ],
            ]);

            Log::info('Refund processed: ' . $refund->id . ' for invoice: ' . $invoice->id);

            return $payment->fresh();
        });
    }

    /**
     * Get payment by invoice ID.
     */
    public function getPaymentByInvoice(int $invoiceId): ?Payment
    {
        return Payment::where('invoice_id', $invoiceId)
            ->orderBy('created_at', 'desc')
            ->first();
    }
}
