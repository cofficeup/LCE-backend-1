<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Pickup\PickupService;
use App\Models\UserSubscription;

class PickupController extends Controller
{
    protected PickupService $pickupService;

    public function __construct(PickupService $pickupService)
    {
        $this->pickupService = $pickupService;
    }

    /**
     * Create pickup preview (PPO or Subscription)
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'order_type' => 'required|in:PPO,subscription',
            'pickup_date' => 'nullable|date',
            'estimated_weight' => 'nullable|numeric|min:0',
            'bags' => 'nullable|integer|min:1',
        ]);

        $user = $request->user(); // auth-agnostic for now

        if ($data['order_type'] === 'PPO') {
            $result = $this->pickupService->createPPOPickup($user, $data);
        } else {
            // Subscription pickup
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
            'data' => $result,
        ]);
    }
}
