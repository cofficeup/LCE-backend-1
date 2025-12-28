<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'lce_user_info';

    protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
        'phone',
        'default_order_type',
        'subscription_id',
    ];

    protected $hidden = [
        'password',
    ];

    // Relationships
    public function pickups()
    {
        return $this->hasMany(Pickup::class, 'user_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'user_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    public function credits()
    {
        return $this->hasMany(Credit::class, 'user_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class, 'user_id');
    }

    public function activeSubscription()
    {
        return $this->belongsTo(UserSubscription::class, 'subscription_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }
}
