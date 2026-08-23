<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Contracts\JWTSubject;


class DeliveryBoy extends Authenticatable  implements JWTSubject
{
    use HasFactory;


    // public function kyc()
    // {
    //     return $this->hasOne(FranchiseKyc::class, 'franchise_id');
    // }
    public function getJWTIdentifier()
    {
        return $this->getKey(); // The user’s primary key
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public static function getDeliveryBoyServiceStatus()
    {
        $delivery_boy_id = Auth::guard('delboy')->id();

        if (!$delivery_boy_id) {
            return [];
        }

        $deliveryBoy = self::findorfail($delivery_boy_id);

        if (!$deliveryBoy) {
            return [];
        }

        return [
            GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_SPEED => $deliveryBoy->gotogo_speed_post,
            GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_BUSINESS_PARCEL => $deliveryBoy->gotogo_business_parcel,
            GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_REGISTERED => $deliveryBoy->gotogo_post_registered,
            GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED => $deliveryBoy->india_post_speed,
            GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_BUSINESS => $deliveryBoy->india_post_business,
            GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_REGISTERED => $deliveryBoy->india_post_registered,
            GotogoSpeedPostParcel::E2E => $deliveryBoy->e2e,
            GotogoSpeedPostParcel::E2H => $deliveryBoy->e2h,
        ];
    }


    public static function checkServiceStatus($serviceKey)
    {
        $serviceStatuses = self::getDeliveryBoyServiceStatus();
        return $serviceStatuses[$serviceKey] ?? null;
    }

    public function kyc()
    {
        return $this->hasOne(DeliveryBoyKyc::class, 'delivery_boy_id');
    }

    public function pickupDetails()
    {
        return $this->hasMany(PickupDetails::class, 'deliveryboy_id');
    }

    public function frenchise()
    {
        return $this->belongsTO(Frenchise::class, 'franchise_id');
    }
}
