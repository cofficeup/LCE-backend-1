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
     * List invoices (latest first)
     */
    public function index(Request $request)
    {
        $user = $request->user(); // auth later

        $query = Invoice::query()->with('lines')->latest();

        // TEMP: user scoping (remove when admin auth added)
        if ($user) {
            $query->where('user_id', $user->id);
        }

        return response()->json([
            'success' => true,
            'data' => $query->paginate(15),
        ]);
    }

    /**
     * GET /api/v1/invoices/{invoice}
     * Show single invoice
     */
    public function show(Invoice $invoice)
    {
        return response()->json([
            'success' => true,
            'data' => $invoice->load('lines'),
        ]);
    }

    /**
     * POST /api/v1/invoices/{invoice}/pay
     * Move invoice to pending_payment
     */
    public function pay(Invoice $invoice)
    {
        $invoice = $this->invoiceService->markPendingPayment($invoice);

        return response()->json([
            'success' => true,
            'data' => $invoice->fresh()->load('lines'),
        ]);
    }
}
