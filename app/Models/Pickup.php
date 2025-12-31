<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pickup extends Model
{
    use HasFactory;

    protected $table = 'pickups';

    protected $fillable = [
        'user_id',
        'subscription_id',
        'invoice_id',
        'order_type',
        'status',
        'pickup_date',
        'estimated_weight',
        'actual_weight',
        'bags_used',
        'pickup_address',
        'delivery_address',
        'notes',
        'picked_up_at',
        'delivered_at',
    ];

    protected $casts = [
        'pickup_date' => 'date',
        'estimated_weight' => 'decimal:2',
        'actual_weight' => 'decimal:2',
        'picked_up_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    const ORDER_TYPES = ['ppo', 'subscription'];

    const STATUSES = [
        'pending_payment',
        'scheduled',
        'picked_up',
        'processing',
        'ready_for_delivery',
        'delivered',
        'cancelled'
    ];

    /**
     * Relationships
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(UserSubscription::class, 'subscription_id');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Scopes
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopePendingPayment($query)
    {
        return $query->where('status', 'pending_payment');
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}
