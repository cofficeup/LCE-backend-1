<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\Pickup\PickupService;
use App\Services\Invoice\InvoiceService;
use App\Models\Invoice;
use App\Models\UserSubscription;

class PickupController extends Controller
{
    protected PickupService $pickupService;
    protected InvoiceService $invoiceService;

    public function __construct(
        PickupService $pickupService,
        InvoiceService $invoiceService
    ) {
        $this->pickupService = $pickupService;
        $this->invoiceService = $invoiceService;
    }

    /**
     * Preview a pickup (does NOT persist anything).
     * 
     * POST /api/v1/pickups/preview
     */
    public function preview(Request $request): JsonResponse
    {
        $data = $request->validate([
            'order_type' => 'required|in:PPO,subscription',
            'pickup_date' => 'nullable|date',
            'estimated_weight' => 'nullable|numeric|min:0',
            'bags' => 'nullable|integer|min:1',
        ]);

        $user = $request->user();

        if ($data['order_type'] === 'PPO') {
            $result = $this->pickupService->createPPOPickup($user, $data);
        } else {
            $subscription = UserSubscription::where('user_id', $user->id)
                ->where('status', 'active')
                ->firstOrFail();

            $result = $this->pickupService->createSubscriptionPickup(
                $user,
                $subscription,
                $data
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Pickup preview generated. Use /pickups/confirm to finalize.',
            'data' => [
                'pickup_preview' => $result['pickup_payload'],
                'billing_preview' => $result['billing_preview'],
                'invoice_preview' => $result['invoice_preview'],
            ],
        ]);
    }

    /**
     * Confirm and persist a pickup + invoice atomically.
     * 
     * POST /api/v1/pickups/confirm
     */
    public function confirm(Request $request): JsonResponse
    {
        $data = $request->validate([
            'order_type' => 'required|in:PPO,subscription',
            'pickup_date' => 'required|date',
            'estimated_weight' => 'nullable|numeric|min:0',
            'bags' => 'nullable|integer|min:1',
            'subscription_id' => 'nullable|integer',
            'invoice_type' => 'required|string',
            'billing_preview' => 'required|array',
        ]);

        $result = $this->pickupService->confirmPickup(
            $request->user(),
            $data
        );

        return response()->json([
            'success' => true,
            'message' => 'Pickup confirmed and invoice created.',
            'data' => $result,
        ]);
    }

    /**
     * Update a pickup (placeholder for future implementation).
     * 
     * PUT /api/v1/pickups/{id}
     */
    public function update(Request $request, int $id): JsonResponse
    {
        // TODO: Implement pickup update logic
        return response()->json([
            'success' => false,
            'message' => 'Pickup update not yet implemented.',
        ], 501);
    }
}
