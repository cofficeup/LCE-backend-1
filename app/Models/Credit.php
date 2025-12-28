<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    protected $table = 'lce_user_credits';

    protected $fillable = [
        'user_id',
        'type',
        'description',
        'amount',
        'balance',
        'expires_at',
        'used',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used' => 'boolean',
    ];
}
