<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPHBag extends Model
{
    use HasFactory;
    protected $guarded = [];


    public const SERVICE_TYPE_GOTO_POST_SPEED = 1;
    public const SERVICE_TYPE_GOTO_POST_SUPERFAST = 2;
    public const SERVICE_TYPE_GOTO_POST_BUSINESS_PARCEL = 3;
    public const SERVICE_TYPE_GOTO_POST_REGISTERED = 4;
    public const SERVICE_TYPE_INDIA_POST_SPEED = 5;
    public const SERVICE_TYPE_INDIA_POST_BUSINESS = 6;
    public const SERVICE_TYPE_INDIA_POST_REGISTERED = 7;

    protected static $serviceModel = [
        self::SERVICE_TYPE_GOTO_POST_SPEED => \App\Models\GotogoSpeedPostParcel::class,
        self::SERVICE_TYPE_GOTO_POST_SUPERFAST => \App\Models\GotogoSuperFastParcel::class,
        self::SERVICE_TYPE_GOTO_POST_BUSINESS_PARCEL => \App\Models\GotogoBusinessParcel::class,
        self::SERVICE_TYPE_GOTO_POST_REGISTERED => \App\Models\GotogoRegisteredParcel::class,
        self::SERVICE_TYPE_INDIA_POST_SPEED => \App\Models\IndiaPostSpeedPostParcel::class,
        self::SERVICE_TYPE_INDIA_POST_BUSINESS => \App\Models\IndiaPostBusinessParcel::class,
        self::SERVICE_TYPE_INDIA_POST_REGISTERED => \App\Models\IndiaPostRegisteredParcel::class,
    ];

    protected static $serviceType = [
        self::SERVICE_TYPE_GOTO_POST_SPEED => 'GOTOGO Post Speed',
        self::SERVICE_TYPE_GOTO_POST_SUPERFAST => 'GOTOGO Post SuperFast',
        self::SERVICE_TYPE_GOTO_POST_BUSINESS_PARCEL => 'GOTOGO Post Business Parcel',
        self::SERVICE_TYPE_GOTO_POST_REGISTERED => 'GOTOGO Post Registered',
        self::SERVICE_TYPE_INDIA_POST_SPEED => 'India Post Speed',
        self::SERVICE_TYPE_INDIA_POST_BUSINESS => 'India Post Business',
        self::SERVICE_TYPE_INDIA_POST_REGISTERED => 'India Post Registered',
    ];

    public static function getServiceType($id)
    {

        return self::$serviceType[$id] ?? null;
    }


    public static function getServiceModel($id)
    {
        return self::$serviceModel[$id] ?? null;
    }


    public function pph()
    {
        return $this->belongsTo(PPH::class);
    }

    public function cms()
    {
        return $this->belongsTo(CMS::class, 'cms_id');
    }

}
