<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\Payment\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Create a Payment Intent for an invoice.
     * 
     * POST /api/v1/payments/intent
     */
    public function createIntent(Request $request): JsonResponse
    {
        $request->validate([
            'invoice_id' => 'required|integer|exists:invoices,id',
        ]);

        $invoice = Invoice::findOrFail($request->invoice_id);
        $user = $request->user();

        // Authorization: user can only pay their own invoices
        if ($invoice->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to pay this invoice.',
            ], 403);
        }

        try {
            $result = $this->paymentService->createPaymentIntent($invoice, $user);

            return response()->json([
                'success' => true,
                'message' => 'Payment intent created.',
                'data' => [
                    'client_secret' => $result['client_secret'],
                    'payment_intent_id' => $result['payment_intent_id'],
                    'amount' => $result['amount'],
                    'currency' => $result['currency'],
                ],
            ]);
        } catch (\DomainException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create payment intent.',
            ], 500);
        }
    }

    /**
     * Get payment status for an invoice.
     * 
     * GET /api/v1/payments/status/{invoice}
     */
    public function status(Invoice $invoice, Request $request): JsonResponse
    {
        $user = $request->user();

        // Authorization
        if ($invoice->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.',
            ], 403);
        }

        $payment = $this->paymentService->getPaymentByInvoice($invoice->id);

        return response()->json([
            'success' => true,
            'data' => [
                'invoice_status' => $invoice->status,
                'payment' => $payment ? [
                    'id' => $payment->id,
                    'status' => $payment->status,
                    'amount' => $payment->amount,
                    'paid_at' => $payment->paid_at,
                ] : null,
            ],
        ]);
    }
}
