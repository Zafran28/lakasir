<?php

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Model;

class FinanceTransaction extends Model
{
    protected $fillable = [
        'transaction_no',
        'transaction_date',
        'type',
        'category',
        'amount',
        'payment_method',
        'description',
        'created_by',
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
        'amount' => 'integer',
    ];
}