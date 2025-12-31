<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Subscription\SubscriptionService;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;

class SubscriptionController extends Controller
{
    protected SubscriptionService $subscriptions;

    public function __construct(SubscriptionService $subscriptions)
    {
        $this->subscriptions = $subscriptions;
    }

    /**
     * Create subscription (pending)
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'plan_id' => 'required|integer',
            'billing_cycle' => 'required|in:monthly,annual',
        ]);

        $plan = SubscriptionPlan::findOrFail($data['plan_id']);

        $subscription = $this->subscriptions->create(
            $request->user(),
            $plan,
            $data['billing_cycle']
        );

        return response()->json([
            'success' => true,
            'data' => $subscription,
        ]);
    }

    /**
     * Activate subscription (after payment)
     * Ownership check: user can only activate their own subscriptions
     */
    public function activate(Request $request, $id)
    {
        $user = $request->user();
        $subscription = UserSubscription::findOrFail($id);

        // Ownership check
        if ($subscription->user_id !== $user->id && !$user->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to activate this subscription.',
            ], 403);
        }

        $activated = $this->subscriptions->activate($subscription);

        return response()->json([
            'success' => true,
            'data' => $activated,
        ]);
    }

    /**
     * Cancel subscription
     * Ownership check: user can only cancel their own subscriptions
     */
    public function cancel(Request $request, $id)
    {
        $user = $request->user();
        $subscription = UserSubscription::findOrFail($id);

        // Ownership check
        if ($subscription->user_id !== $user->id && !$user->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to cancel this subscription.',
            ], 403);
        }

        $cancelled = $this->subscriptions->cancel(
            $subscription,
            $request->input('reason')
        );

        return response()->json([
            'success' => true,
            'data' => $cancelled,
        ]);
    }
}
