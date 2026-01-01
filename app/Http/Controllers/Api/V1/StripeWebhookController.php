<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\StripeWebhookEvent;
use App\Services\Stripe\StripeService;
use App\Services\Payment\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends Controller
{
    protected StripeService $stripeService;
    protected PaymentService $paymentService;

    public function __construct(StripeService $stripeService, PaymentService $paymentService)
    {
        $this->stripeService = $stripeService;
        $this->paymentService = $paymentService;
    }

    /**
     * Handle Stripe webhook events.
     * 
     * POST /api/v1/webhooks/stripe
     */
    public function handle(Request $request): JsonResponse
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        if (!$signature) {
            Log::warning('Stripe webhook: Missing signature');
            return response()->json(['error' => 'Missing signature'], 400);
        }

        // Verify webhook signature
        try {
            $event = $this->stripeService->constructWebhookEvent($payload, $signature);
        } catch (\RuntimeException $e) {
            Log::warning('Stripe webhook: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Idempotency check: store event
        $webhookEvent = StripeWebhookEvent::findOrCreateEvent(
            $event->id,
            $event->type,
            $event->toArray()
        );

        if ($webhookEvent->isProcessed()) {
            Log::info('Stripe webhook: Event already processed - ' . $event->id);
            return response()->json(['status' => 'already_processed']);
        }

        // Handle event types
        try {
            $this->processEvent($event);
            $webhookEvent->markProcessed();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Stripe webhook processing failed: ' . $e->getMessage(), [
                'event_id' => $event->id,
                'type' => $event->type,
            ]);

            return response()->json(['error' => 'Processing failed'], 500);
        }
    }

    /**
     * Process specific event types.
     */
    protected function processEvent(\Stripe\Event $event): void
    {
        $paymentIntent = $event->data->object;

        switch ($event->type) {
            case 'payment_intent.succeeded':
                Log::info('Processing payment_intent.succeeded: ' . $paymentIntent->id);
                $this->paymentService->markPaymentSucceeded($paymentIntent->id);
                break;

            case 'payment_intent.payment_failed':
                Log::info('Processing payment_intent.payment_failed: ' . $paymentIntent->id);
                $failureMessage = $paymentIntent->last_payment_error->message ?? 'Payment failed';
                $this->paymentService->markPaymentFailed($paymentIntent->id, $failureMessage);
                break;

            case 'charge.refunded':
                Log::info('Processing charge.refunded: ' . $paymentIntent->id);
                // Refunds are handled via admin action, not webhook
                break;

            default:
                Log::info('Unhandled Stripe event type: ' . $event->type);
        }
    }
}
