<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\GotogoSpeedPostParcel;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Auth;

class Franchise extends Authenticatable implements JWTSubject
{
    use HasFactory, HasRoles, Notifiable;
    // Implement the methods required by the JWTSubject interface

    /**
     * Get the identifier that will be stored in the JWT claim.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function franchaisepayment()
    {
        return $this->belongsTo('App\Models\FranchisePayment', 'id', 'franchise_id');
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function kyc()
    {
        return $this->hasOne(FranchiseKyc::class, 'franchise_id');
    }

    public static function getFranchiseId()
    {
        if (Auth::guard('franchise')->check()) {
            return Auth::guard('franchise')->id();
        } elseif (Auth::guard('franchiseRoleUser')->check()) {
            return Auth::guard('franchiseRoleUser')->user()->franchise_id;
        } else {
            return null;
        }
    }

    public static function getFranchiseServiceStatus()
    {
        $franchiseId = self::getFranchiseId(); // Get the franchise ID

        if (!$franchiseId) {
            return []; // Return an empty array if no franchise ID is found
        }

        $franchise = self::findorfail($franchiseId);

        if (!$franchise) {
            return []; // Return an empty array if franchise not found
        }

        return [
            GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_SPEED => $franchise->gotogo_speed_post,
            GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_BUSINESS_PARCEL => $franchise->gotogo_business_parcel,
            GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_REGISTERED => $franchise->gotogo_post_registered,
            GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED => $franchise->india_post_speed,
            GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_BUSINESS => $franchise->india_post_business,
            GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_REGISTERED => $franchise->india_post_registered,
            GotogoSpeedPostParcel::E2E => $franchise->e2e,
            GotogoSpeedPostParcel::E2H => $franchise->e2h,
        ];
    }


    public static function checkServiceStatus($serviceKey)
    {
        $serviceStatuses = self::getFranchiseServiceStatus();

        return $serviceStatuses[$serviceKey] ?? null;
    }


    public static function getLoggedINUserId()
    {
        if (Auth::guard('franchise')->check()) {
            return Auth::guard('franchise')->id();
        } elseif (Auth::guard('franchiseRoleUser')->check()) {
            return Auth::guard('franchiseRoleUser')->user()->id;
        } else {
            return null;
        }
    }


    public function routeNotificationForFcm()
    {
        return $this->fcm_token ?: null; // Prevents errors if fcm_token is missing
    }
    
    public function pickupDetails()
{
    return $this->hasMany(PickupDetails::class, 'franchise_id');
}

   public function noregister()
    {
        return $this->belongsTo(NoRegisterCustomer::class, 'franchise_id');
    }



    protected $guarded = [];
}
