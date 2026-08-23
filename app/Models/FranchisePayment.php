<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FranchisePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'franchise_id',
        'razorpay_payment_id',
        'amount',
        'status',
        'method',
        'type'
    ];

    public function franchise()
    {
        return $this->hasOne('App\Models\Franchise', 'id', 'franchise_id');
    }
}
