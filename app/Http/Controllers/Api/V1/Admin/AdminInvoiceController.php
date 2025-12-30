<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\Invoice\InvoiceService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminInvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::query()->with('lines')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        if ($request->filled('min')) {
            $query->where('total', '>=', $request->min);
        }

        if ($request->filled('max')) {
            $query->where('total', '<=', $request->max);
        }

        return response()->json([
            'success' => true,
            'data' => $query->paginate(25),
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $query = Invoice::query()->latest();

        foreach (['status','type','user_id'] as $field) {
            if ($request->filled($field)) {
                $query->where($field, $request->$field);
            }
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $invoices = $query->get();

        return response()->streamDownload(function () use ($invoices) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Invoice ID','User ID','Type','Status',
                'Subtotal','Tax','Total','Currency','Created At'
            ]);

            foreach ($invoices as $invoice) {
                fputcsv($handle, [
                    $invoice->id,
                    $invoice->user_id,
                    $invoice->type,
                    $invoice->status,
                    $invoice->subtotal,
                    $invoice->tax,
                    $invoice->total,
                    $invoice->currency,
                    $invoice->created_at,
                ]);
            }

            fclose($handle);
        }, 'invoices_export.csv');
    }

    public function refund(Request $request, Invoice $invoice, InvoiceService $invoiceService)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        if ($invoice->status !== 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Only paid invoices can be refunded.',
            ], 422);
        }

        $invoice = $invoiceService->markRefunded(
            $invoice,
            $request->reason
        );

        return response()->json([
            'success' => true,
            'data' => $invoice->fresh()->load('lines'),
        ]);
    }
}
