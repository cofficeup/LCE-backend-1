<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Pickup\PickupService;
use App\Services\Invoice\InvoiceService;
use App\Models\User;
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
     * Create pickup preview (PPO or Subscription) and persist invoice.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'order_type' => 'required|in:PPO,subscription',
            'pickup_date' => 'nullable|date',
            'estimated_weight' => 'nullable|numeric|min:0',
            'bags' => 'nullable|integer|min:1',
        ]);

        // ---------------------------------------------------------
        // TEMP: Fallback to first user when auth is not implemented
        // TODO: Replace with $request->user() once Sanctum is added
        // ---------------------------------------------------------
        $user = $request->user() ?? User::firstOrFail();

        if ($data['order_type'] === 'PPO') {
            // Get pickup & billing preview from service
            $result = $this->pickupService->createPPOPickup($user, $data);

            // Persist the invoice draft to DB
            $invoice = $this->invoiceService->createAndPersistDraft(
                userId: $user->id,
                invoiceType: 'ppo', // lowercase to match INVOICE_TYPES enum
                billingPreview: $result['billing_preview']
            );
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

            // Persist the invoice draft to DB
            $invoice = $this->invoiceService->createAndPersistDraft(
                userId: $user->id,
                invoiceType: 'subscription_overage', // correct enum value
                billingPreview: $result['billing_preview'],
                subscriptionId: $subscription->id
            );
        }

        return response()->json([
            'success' => true,
            'data' => [
                'pickup_preview' => $result['pickup_payload'],
                'billing_preview' => $result['billing_preview'],
                'invoice' => $invoice->load('lines'), // Return persisted invoice with lines
            ],
        ]);
    }
}
