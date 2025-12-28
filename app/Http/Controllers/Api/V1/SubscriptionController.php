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
     */
    public function activate($id)
    {
        $subscription = UserSubscription::findOrFail($id);

        $activated = $this->subscriptions->activate($subscription);

        return response()->json([
            'success' => true,
            'data' => $activated,
        ]);
    }

    /**
     * Cancel subscription
     */
    public function cancel(Request $request, $id)
    {
        $subscription = UserSubscription::findOrFail($id);

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
