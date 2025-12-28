<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $table = 'lce_subscription_plans';

    protected $fillable = [
        'code',
        'name',
        'bags_per_month',
        'price_per_bag',
        'billing_cycle',
        'annual_discount',
        'active',
    ];

    public function userSubscriptions()
    {
        return $this->hasMany(UserSubscription::class, 'plan_id');
    }
}
