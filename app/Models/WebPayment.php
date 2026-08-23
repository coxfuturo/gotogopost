<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'razorpay_payment_id',
        'amount',
        'status',
        'method'
    ];
}
