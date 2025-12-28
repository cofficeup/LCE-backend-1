<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pickup extends Model
{
    protected $table = 'lce_user_pickup';

    protected $fillable = [
        'user_id',
        'order_type',
        'pickup_date',
        'delivery_date',
        'subscription_id',
        'subscription_bags',
        'subscription_overweight_lbs',
        'subscription_overweight_charge',
    ];

    protected $casts = [
        'pickup_date' => 'datetime',
        'delivery_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(UserSubscription::class, 'subscription_id');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'pickup_id');
    }
}
