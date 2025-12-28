<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceLine extends Model
{
    protected $table = 'lce_user_invoice_line';

    protected $fillable = [
        'invoice_id',
        'type',
        'quantity',
        'unit_price',
        'total',
    ];
}
