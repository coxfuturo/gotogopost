<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Auth;

class ECustomer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'register_type',
        'customer_no',
        'name',
        'father_name',
        'mobile',
        'gst_number',
        'cph_link',
        'location',
        'email',
        'pincode',
        'city',
        'district',
        'state',
        'code',
        'address',
        'latitude',
        'longitude',
        'society',
        'sector',
        'status',
        'cod_charges',
        'delivery_charges',
        'payment_status',
        'commission',
        'password',
        'payment_account_no',
        'amount_credited',
        'imps_no',
        'payment_date',
        'gotogo_business_parcel',
        'india_post_speed',
        'india_post_business',
        'india_post_registered',
        'verification_otp',
        'fcm_token',
        'gotogo_balance',
        'indiapost_balance',
        'credit_balance',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function kyc()
    {
        return $this->hasOne(ECustomerKyc::class, 'e_customer_id');
    }

    public function GotogoBusinessParcel()
    {
        return $this->hasMany(ECustomer::class, 'cod_customer_id');
    }
}
