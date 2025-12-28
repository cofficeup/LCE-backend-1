<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'lce_user_invoice';

    protected $fillable = [
        'user_id',
        'pickup_id',
        'subscription_id',
        'order_type',
        'sub_total',
        'discount',
        'total',
        'status',
    ];

    public function lines()
    {
        return $this->hasMany(InvoiceLine::class, 'invoice_id');
    }

    public function pickup()
    {
        return $this->belongsTo(Pickup::class, 'pickup_id');
    }
}
