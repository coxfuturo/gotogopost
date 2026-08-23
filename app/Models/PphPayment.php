<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PphPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'pph_id', // Add the field you're trying to assign
        'razorpay_payment_id',
        'amount',
        'status',
        'method'
    ];

    public function pph()
    {
        return $this->hasOne('App\Models\PPH', 'id','pph_id');
    }
}
