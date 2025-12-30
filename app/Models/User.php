<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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
        'remember_token',
    ];

    /**
     * ------------------
     * Relationships
     * ------------------
     */
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

    /**
     * ------------------
     * Roles & Permissions
     * ------------------
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }
}
