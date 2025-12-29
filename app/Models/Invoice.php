<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'user_id',
        'pickup_id',
        'subscription_id',
        'type',
        'status',
        'currency',
        'subtotal',
        'tax',
        'total',
        'metadata',
        'issued_at',
        'paid_at',
        'refunded_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'issued_at' => 'datetime',
        'paid_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    public function lines()
    {
        return $this->hasMany(InvoiceLine::class);
    }
}
