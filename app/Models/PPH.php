<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class PPH extends Authenticatable
{
    use HasFactory;

    protected $guarded = [];

    public function kyc()
    {
        return $this->hasOne(PPHKyc::class, 'pph_id');
    }

    public function pphpayment()
    {
        return $this->belongsTo('App\Models\PphPayment', 'id','pph_id');
    }


    public static function getPPHServiceStatus()
    {
        $pphID = Auth::guard('pph')->id();

        if (!$pphID) {
            return [];
        }

        $pph = self::findorfail($pphID);

        if (!$pph) {
            return []; // Return an empty array if franchise not found
        }

        return [
            GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_SPEED => $pph->gotogo_speed_post,
            GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_BUSINESS_PARCEL => $pph->gotogo_business_parcel,
            GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_REGISTERED => $pph->gotogo_post_registered,
            GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED => $pph->india_post_speed,
            GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_BUSINESS => $pph->india_post_business,
            GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_REGISTERED => $pph->india_post_registered,
            GotogoSpeedPostParcel::E2E => $pph->e2e,
            GotogoSpeedPostParcel::E2H => $pph->e2h,
        ];
    }


    public static function checkServiceStatus($serviceKey)
    {
        $serviceStatuses = self::getPPHServiceStatus();
        return $serviceStatuses[$serviceKey] ?? null;
    }
}
