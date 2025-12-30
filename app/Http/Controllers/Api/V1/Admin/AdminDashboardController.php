<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AdminDashboardController extends Controller
{
    /**
     * GET /api/v1/admin/dashboard/summary
     */
    public function summary()
    {
        $paidInvoices = Invoice::where('status', 'paid');

        return response()->json([
            'success' => true,
            'data' => [
                'total_revenue' => $paidInvoices->sum('total'),
                'total_invoices' => Invoice::count(),
                'paid_invoices' => Invoice::where('status', 'paid')->count(),
                'refunded_invoices' => Invoice::where('status', 'refunded')->count(),
                'average_order_value' => round(
                    $paidInvoices->avg('total') ?? 0,
                    2
                ),
            ],
        ]);
    }

    /**
     * GET /api/v1/admin/dashboard/revenue
     * ?from=YYYY-MM-DD&to=YYYY-MM-DD
     */
    public function revenue(Request $request)
    {
        $query = Invoice::where('status', 'paid');

        if ($request->filled('from')) {
            $query->whereDate('paid_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('paid_at', '<=', $request->to);
        }

        $data = $query
            ->select(
                DB::raw('DATE(paid_at) as date'),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as invoices')
            )
            ->groupBy(DB::raw('DATE(paid_at)'))
            ->orderBy('date')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
