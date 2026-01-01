<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StripeWebhookEvent extends Model
{
    protected $fillable = [
        'stripe_event_id',
        'type',
        'payload',
        'processed_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'processed_at' => 'datetime',
    ];

    /**
     * Check if this event was already processed.
     */
    public function isProcessed(): bool
    {
        return $this->processed_at !== null;
    }

    /**
     * Mark event as processed.
     */
    public function markProcessed(): void
    {
        $this->update(['processed_at' => now()]);
    }

    /**
     * Find or create event for idempotency check.
     */
    public static function findOrCreateEvent(string $eventId, string $type, array $payload): self
    {
        return self::firstOrCreate(
            ['stripe_event_id' => $eventId],
            ['type' => $type, 'payload' => $payload]
        );
    }
}
