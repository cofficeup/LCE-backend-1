<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Billing\BillingService;

class BillingController extends Controller
{
    protected BillingService $billing;

    public function __construct(BillingService $billing)
    {
        $this->billing = $billing;
    }

    /**
     * PPO price preview
     */
    public function ppoPreview(Request $request)
    {
        $data = $request->validate([
            'weight_lbs' => 'required|numeric|min:0.1',
        ]);

        // Example static values (later from DB)
        $result = $this->billing->billPPO(
            $request->user(),
            $data['weight_lbs'],
            1.99,   // price per lb
            30.00,  // minimum
            5.00,   // pickup fee
            3.00    // service fee
        );

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }
}
