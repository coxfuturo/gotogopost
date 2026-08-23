<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Models\PPH;

class PPHBarcodeSeries extends Model
{
    use HasFactory;


    protected $guarded = [];

    public static $PARCELCODE = "P";
    public static $BAGCODE = "B";

    public const SERVICE_TYPE_GOTO_POST_SPEED = 1;
    public const SERVICE_TYPE_GOTO_POST_SUPERFAST = 2;
    public const SERVICE_TYPE_GOTO_POST_BUSINESS_PARCEL = 3;
    public const SERVICE_TYPE_GOTO_POST_REGISTERED = 4;
    public const SERVICE_TYPE_INDIA_POST_SPEED = 5;
    public const SERVICE_TYPE_INDIA_POST_BUSINESS = 6;
    public const SERVICE_TYPE_INDIA_POST_REGISTERED = 7;

    protected static $serviceType = [
        self::SERVICE_TYPE_GOTO_POST_SPEED => 'GOTOGO Post Speed',
        self::SERVICE_TYPE_GOTO_POST_SUPERFAST => 'GOTOGO Post SuperFast',
        self::SERVICE_TYPE_GOTO_POST_BUSINESS_PARCEL => 'GOTOGO Post Business Parcel',
        self::SERVICE_TYPE_GOTO_POST_REGISTERED => 'GOTOGO Post Registered',
        self::SERVICE_TYPE_INDIA_POST_SPEED => 'India Post Speed',
        self::SERVICE_TYPE_INDIA_POST_BUSINESS => 'India Post Business',
        self::SERVICE_TYPE_INDIA_POST_REGISTERED => 'India Post Registered',
    ];


    protected static $serviceCode = [
        self::SERVICE_TYPE_GOTO_POST_SPEED => 'S',
        self::SERVICE_TYPE_GOTO_POST_SUPERFAST => 'F',
        self::SERVICE_TYPE_GOTO_POST_BUSINESS_PARCEL => 'B',
        self::SERVICE_TYPE_GOTO_POST_REGISTERED => 'R',
        self::SERVICE_TYPE_INDIA_POST_SPEED => 'S',
        self::SERVICE_TYPE_INDIA_POST_BUSINESS => 'B',
        self::SERVICE_TYPE_INDIA_POST_REGISTERED => 'R',
    ];


    protected static $serviceTypeDB = [
        self::SERVICE_TYPE_GOTO_POST_SPEED => 'gotoSpeed',
        self::SERVICE_TYPE_GOTO_POST_SUPERFAST => 'gotoSuperFast',
        self::SERVICE_TYPE_GOTO_POST_BUSINESS_PARCEL => 'gotoBusiness',
        self::SERVICE_TYPE_GOTO_POST_REGISTERED => 'gotoRegistered',
        self::SERVICE_TYPE_INDIA_POST_SPEED => 'IPSpeed',
        self::SERVICE_TYPE_INDIA_POST_BUSINESS => 'IPBusiness',
        self::SERVICE_TYPE_INDIA_POST_REGISTERED => 'IPRegistered',
    ];

    public static function getServiceType($id)
    {

        return self::$serviceType[$id] ?? null;
    }


    public static function getServiceCode($id)
    {
        return  self::$serviceCode[$id]  ?? null;
    }

    public static function getServiceTypeDB($id)
    {
        return self::$serviceTypeDB[$id] ?? null;
    }

    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if ($key == 'barcode_no' || $key == 'barcode_image_src') {
            return $value;
        }

        if (is_string($value)) {
            return ucwords(strtolower($value));
        }

        return $value;
    }


    public function barcodes()
    {
        return $this->hasMany(PPHBarcodes::class, 'pph_barcodeseries_id');
    }


    public function pph()
    {
        return $this->belongsTo(PPH::class);
    }
}
