<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CMSPayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'cms_id', 
        'razorpay_payment_id',
        'amount',
        'status',
        'method'
    ];

    public function cms()
    {
        return $this->hasOne('App\Models\CMS', 'id','cms_id');
    }
}
