<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryBoyKyc extends Model
{
    use HasFactory;
    protected $table = 'deliveryBoy_kycs';
    protected $fillable = [
        'franchise_id',
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
        'video_kyc',
        'status',
        'applied_at',
        'approved_at',
        'rejected_at',
        'reject_reason',
    ];

    public function deliveryBoy()
    {
        return $this->belongsTo(deliveryBoy::class,'delivery_boy_id');
    }
}
