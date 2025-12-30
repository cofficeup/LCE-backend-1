<?php

namespace App\Services\Pickup;

use App\Models\User;
use App\Models\UserSubscription;
use App\Services\Billing\BillingService;
use App\Services\Subscription\SubscriptionService;
use App\Services\Invoice\InvoiceService;


class PickupService
{
    protected BillingService $billing;
    protected SubscriptionService $subscriptions;
    protected InvoiceService $invoices;

    public function __construct(
        BillingService $billing,
        SubscriptionService $subscriptions,
        InvoiceService $invoices
    ) {
        $this->billing = $billing;
        $this->subscriptions = $subscriptions;
        $this->invoices = $invoices;
    }

    /**
     * Create a PPO (Pay-Per-Order) pickup preview.
     */
    public function createPPOPickup(User $user, array $data): array
    {
        // 1️⃣ Validate input
        if (empty($data['estimated_weight']) || $data['estimated_weight'] <= 0) {
            throw new \InvalidArgumentException('Estimated weight must be greater than zero.');
        }

        // 2️⃣ Billing preview (static pricing for now; later from DB)
        $billingPreview = $this->billing->billPPO(
            $user,
            $data['estimated_weight'],
            1.99,   // price per lb (placeholder)
            30.00,  // minimum charge
            5.00,   // pickup fee
            3.00    // service fee
        );
        // TEMP: legacy DB not available yet

        // Get user ID  Newly Add********
        $userId = $user->id ?? 1;

        // Create invoice draft (preview only, not persisted)
        $invoicePreview = $this->invoices->createDraft(
            userId: $userId,
            invoiceType: 'ppo',
            billingPreview: $billingPreview,
            pickupId: null
        );

        //Newly Add end********

        // 3️⃣ Prepare pickup payload (NOT saved yet)
        $pickupPayload = [
            'order_type' => 'PPO',
            'status' => $billingPreview['requires_payment']
                ? 'pending_payment'
                : 'scheduled',

            'pickup_date' => $data['pickup_date'] ?? null,
            'estimated_weight' => $data['estimated_weight'],

            'bags_used' => null,
            'subscription_id' => null,
        ];

        return [
            'pickup_payload' => $pickupPayload,
            'billing_preview' => $billingPreview,
            'invoice_preview' => $invoicePreview,
        ];
    }

    /**
     * Create a subscription pickup preview.
     */
    public function createSubscriptionPickup(
        User $user,
        UserSubscription $subscription,
        array $data
    ): array {
        // 1️⃣ Validate subscription
        if ($subscription->status !== 'active') {
            throw new \DomainException('Subscription must be active to create a pickup.');
        }

        if (empty($data['bags']) || $data['bags'] <= 0) {
            throw new \InvalidArgumentException('At least one bag is required.');
        }

        // 2️⃣ Check available bags
        $availableBags = $this->subscriptions
            ->calculateAvailableBags($subscription);

        if ($data['bags'] > $availableBags) {
            throw new \DomainException('Not enough subscription bags available.');
        }

        // 3️⃣ Billing preview (overage only)
        $billingPreview = $this->billing->billSubscriptionOverage(
            $user,
            $subscription,
            $data['estimated_weight'] ?? 0,
            $data['bags'],
            20,     // max weight per bag (lbs)
            2.50    // overage price per lb
        );

        // 4️⃣ Prepare pickup payload
        $pickupPayload = [
            'order_type' => 'subscription',
            'status' => 'scheduled',

            'pickup_date' => $data['pickup_date'] ?? null,
            'estimated_weight' => $data['estimated_weight'] ?? null,

            'bags_used' => $data['bags'],
            'subscription_id' => $subscription->id,
        ];
        //Newly Add********
        $userId = $user->id ?? 1;

        $invoicePreview = $this->invoices->createDraft(
            userId: $userId,
            invoiceType: 'subscription_overage',
            billingPreview: $billingPreview,
            pickupId: null,
            subscriptionId: $subscription->id
        );
        //Newly Add end********
        return [
            'pickup_payload' => $pickupPayload,
            'billing_preview' => $billingPreview,
            'invoice_preview' => $invoicePreview,
        ];
    }
}
