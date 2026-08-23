<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class CMS extends Authenticatable
{
    use HasFactory;

    protected $guarded = [];

    public function kyc()
    {
        return $this->hasOne(CMSKyc::class, 'cms_id');
    }

    public function cmspayment()
    {
        return $this->belongsTo('App\Models\CMSPayment', 'id','cms_id');
    }


    public static function getCMSServiceStatus()
    {
        $cmsID = Auth::guard('cms')->id();

        if (!$cmsID) {
            return [];
        }

        $cms = self::findorfail($cmsID);

        if (!$cms) {
            return []; // Return an empty array if franchise not found
        }

        return [
            GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_SPEED => $cms->gotogo_speed_post,
            GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_BUSINESS_PARCEL => $cms->gotogo_business_parcel,
            GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_REGISTERED => $cms->gotogo_post_registered,
            GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED => $cms->india_post_speed,
            GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_BUSINESS => $cms->india_post_business,
            GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_REGISTERED => $cms->india_post_registered,
            GotogoSpeedPostParcel::E2E => $cms->e2e,
            GotogoSpeedPostParcel::E2H => $cms->e2h,
        ];
    }


    public static function checkServiceStatus($serviceKey)
    {
        $serviceStatuses = self::getCMSServiceStatus();

        return $serviceStatuses[$serviceKey] ?? null;
    }
}
