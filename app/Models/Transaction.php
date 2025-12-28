<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'lce_user_transactions';

    protected $fillable = [
        'user_id',
        'subscription_id',
        'type',
        'amount',
        'description',
        'transactionId',
    ];
}
