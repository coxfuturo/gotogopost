<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ECustomerKyc extends Model
{
   use HasFactory;
    protected $table = 'e_customer_kycs';
    protected $fillable = [
        'e_customer_id',
        'adhar_card',
        'pan_card',
        'ifsc_code',
        'bank_name',
        'branch_name',
        'account_number',
        'adhar_front_img',
        'adhar_back_img',
        'cheque_img',
        'photo',
        'status',
        'applied_at',
        'approved_at',
        'rejected_at',
        'reject_reason',
    ];

    public function ecustomer()
    {
        return $this->belongsTo(ECustomer::class,'e_customer_id');
    }
}
