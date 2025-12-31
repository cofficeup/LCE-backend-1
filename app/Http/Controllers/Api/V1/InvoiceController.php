<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\Invoice\InvoiceService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    protected InvoiceService $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    /**
     * GET /api/v1/invoices
     * List invoices for current user (latest first)
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Invoice::query()
            ->with('lines')
            ->where('user_id', $user->id)
            ->latest();

        return response()->json([
            'success' => true,
            'data' => $query->paginate(15),
        ]);
    }

    /**
     * GET /api/v1/invoices/{invoice}
     * Show single invoice (ownership check)
     */
    public function show(Request $request, Invoice $invoice)
    {
        $user = $request->user();

        // Ownership check: user can only view their own invoices
        if ($invoice->user_id !== $user->id && !$user->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to view this invoice.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $invoice->load('lines'),
        ]);
    }

    /**
     * POST /api/v1/invoices/{invoice}/pay
     * Move invoice to pending_payment (ownership check)
     */
    public function pay(Request $request, Invoice $invoice)
    {
        $user = $request->user();

        // Ownership check: user can only pay their own invoices
        if ($invoice->user_id !== $user->id && !$user->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to pay this invoice.',
            ], 403);
        }

        $invoice = $this->invoiceService->markPendingPayment($invoice);

        return response()->json([
            'success' => true,
            'data' => $invoice->fresh()->load('lines'),
        ]);
    }
}
