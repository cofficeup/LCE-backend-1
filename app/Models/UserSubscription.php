<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    protected $table = 'lce_user_subscriptions';

    protected $fillable = [
        'user_id',
        'plan_id',
        'status',
        'billing_cycle',
        'start_date',
        'end_date',
        'next_renewal_date',
        'bags_plan_total',
        'bags_plan_balance',
        'bags_plan_used',
        'bags_available',
        'payment_last',
        'payment_discount',
        'payment_balance',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'next_renewal_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    public function bagUsages()
    {
        return $this->hasMany(SubscriptionBagUsage::class, 'user_subscription_id');
    }

    public function pickups()
    {
        return $this->hasMany(Pickup::class, 'subscription_id');
    }
}
