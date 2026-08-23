<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Auth;

class MManager extends Authenticatable
{
     use HasFactory, Notifiable;

     protected $fillable = [
        'generated_id',
        'register_type',
        'customer_no',
        'name',
        'father_name',
        'mobile',
        'gst_number',
        'franchise_id',
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
        'natality',
        'status',
        'cod_charges',
        'delivery_charges',
        'payment_status',
        'commission',
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
        'age',
        'gender'
    ];


      public static function getManagerId()
    {
        if (Auth::guard('market')->check()) {
            return Auth::guard('market')->id();
        } elseif (Auth::guard('marketRoleUser')->check()) {
            return Auth::guard('marketeRoleUser')->user()->id;
        } else {
            return null;
        }
    }

    public function managerkyc()
    {
        return $this->hasOne(MManagerKyc::class, 'm_manager_id');
    }

    public function noregister()
    {
        return $this->hasOne(NoRegisterCustomer::class, 'market_id');
    }
}
