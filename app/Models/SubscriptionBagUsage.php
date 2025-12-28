<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionBagUsage extends Model
{
    protected $table = 'lce_subscription_bag_usage';

    protected $fillable = [
        'user_subscription_id',
        'pickup_id',
        'invoice_id',
        'bags_used',
    ];
}
