<?php

namespace App\Services\Invoice;

class InvoiceService
{
    const INVOICE_TYPES = ['ppo', 'subscription_overage', 'adjustment', 'refund'];
    const STATUSES = ['draft', 'pending_payment', 'paid', 'refunded'];
    const LINE_TYPES = [
        'weight',
        'minimum_adjustment',
        'pickup_fee',
        'service_fee',
        'overage',
        'credit',
        'tax',
    ];

    /**
     * Create a draft invoice from a billing preview.
     * - No DB writes
     * - Accounting-safe math
     */
    public function createDraft(
        int $userId,
        string $invoiceType,
        array $billingPreview,
        ?int $pickupId = null,
        ?int $subscriptionId = null,
        string $currency = 'USD'
    ): array {
        if (!in_array($invoiceType, self::INVOICE_TYPES, true)) {
            throw new \InvalidArgumentException('Invalid invoice type.');
        }

        $lines = [];
        $subtotal = 0.0;

        // -------------------------
        // 1) Pricing → Lines
        // -------------------------
        if (!empty($billingPreview['pricing'])) {
            $pricing = $billingPreview['pricing'];

            // PPO
            if ($invoiceType === 'ppo') {
                // Weight line (strict math)
                if (isset($pricing['weight_lbs'], $pricing['price_per_lb'])) {
                    $weightAmount = $pricing['weight_lbs'] * $pricing['price_per_lb'];
                    $lines[] = $this->line(
                        'weight',
                        'Laundry service (by weight)',
                        (float) $pricing['weight_lbs'],
                        (float) $pricing['price_per_lb']
                    );
                    $subtotal += $weightAmount;
                }

                // Minimum adjustment (if applied)
                if (!empty($pricing['minimum_applied']) && isset($pricing['base_total'])) {
                    $current = $subtotal;
                    $adjustment = max(0, (float) $pricing['base_total'] - $current);

                    if ($adjustment > 0) {
                        $lines[] = $this->line(
                            'minimum_adjustment',
                            'Minimum charge adjustment',
                            1.0,
                            $adjustment
                        );
                        $subtotal += $adjustment;
                    }
                }

                // Pickup fee
                if (!empty($pricing['pickup_fee'])) {
                    $lines[] = $this->line(
                        'pickup_fee',
                        'Pickup fee',
                        1.0,
                        (float) $pricing['pickup_fee']
                    );
                    $subtotal += (float) $pricing['pickup_fee'];
                }

                // Service fee
                if (!empty($pricing['service_fee'])) {
                    $lines[] = $this->line(
                        'service_fee',
                        'Service fee',
                        1.0,
                        (float) $pricing['service_fee']
                    );
                    $subtotal += (float) $pricing['service_fee'];
                }
            }

            // Subscription overage
            if ($invoiceType === 'subscription_overage' && !empty($pricing['overage_charge'])) {
                if (isset($pricing['overweight_lbs'], $pricing['price_per_lb'])) {
                    $lines[] = $this->line(
                        'overage',
                        'Overweight charge',
                        (float) $pricing['overweight_lbs'],
                        (float) $pricing['price_per_lb']
                    );
                    $subtotal += (float) $pricing['overage_charge'];
                }
            }
        }

        // -------------------------
        // 2) Credits (negative line)
        // -------------------------
        if (!empty($billingPreview['credits']['credits_used'])) {
            $creditUsed = (float) $billingPreview['credits']['credits_used'];

            if ($creditUsed > 0) {
                $lines[] = $this->line(
                    'credit',
                    'Credit applied',
                    1.0,
                    -$creditUsed
                );
                $subtotal -= $creditUsed;
            }
        }

        // -------------------------
        // 3) Totals
        // -------------------------
        $subtotal = round($subtotal, 2);
        $tax = 0.0; // future
        $total = max(0.0, round($subtotal + $tax, 2));

        // -------------------------
        // 4) Draft Invoice Header
        // -------------------------
        $invoice = [
            'user_id' => $userId,
            'pickup_id' => $pickupId,
            'subscription_id' => $subscriptionId,
            'type' => $invoiceType,
            'status' => 'draft',
            'currency' => $currency,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'metadata' => [],
            // NOTE: no issued_at for drafts
        ];

        return [
            'invoice' => $invoice,
            'invoice_lines' => $lines,
            'totals' => [
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
            ],
        ];
    }

    /**
     * Helper to build an invoice line.
     * Enforces: amount === quantity × unit_price
     */
    protected function line(
        string $type,
        string $description,
        float $quantity,
        float $unitPrice
    ): array {
        if (!in_array($type, self::LINE_TYPES, true)) {
            throw new \InvalidArgumentException('Invalid invoice line type.');
        }

        $quantity = round($quantity, 2);
        $unitPrice = round($unitPrice, 2);
        $amount = round($quantity * $unitPrice, 2);

        return [
            'type' => $type,
            'description' => $description,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'amount' => $amount,
        ];
    }
}
