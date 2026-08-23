<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Franchise;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class IndiaPostRegisteredParcel extends Model
{
    use HasFactory;

    protected $guarded = [];


    public const INSERT_TYPE_SINGLE = 1;
    public const INSERT_TYPE_BULK = 2;
    public const INSERT_TYPE_APP = 3;

    public const SERVICE_TYPE_GOTO_POST_SPEED = 1;
    public const SERVICE_TYPE_GOTO_POST_SUPERFAST = 2;
    public const SERVICE_TYPE_GOTO_POST_BUSINESS_PARCEL = 3;
    public const SERVICE_TYPE_GOTO_POST_REGISTERED = 4;
    public const SERVICE_TYPE_INDIA_POST_SPEED = 5;
    public const SERVICE_TYPE_INDIA_POST_BUSINESS = 6;
    public const SERVICE_TYPE_INDIA_POST_REGISTERED = 7;
    public const E2E = 8;
    public const E2H = 9;

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
        self::SERVICE_TYPE_INDIA_POST_SPEED => 'D',
        self::SERVICE_TYPE_INDIA_POST_BUSINESS => 'N',
        self::SERVICE_TYPE_INDIA_POST_REGISTERED => 'P',
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

    public static function getServiceCodeForApi($service_type, $franchise_id)
    {
        $franchise_state = Franchise::where('id', $franchise_id)->select('state')->first();

        return $franchise_state->state[0] . self::$serviceCode[$service_type]  ?? null;
    }


    public static function getServiceCode($id)
    {
        $franchise_state = Franchise::where('id', Franchise::getFranchiseId())->select('state')->first();

        return $franchise_state->state[0] . self::$serviceCode[$id]  ?? null;
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

    public function franchiseBag()
    {
        return $this->belongsTo(FranchiseBag::class, 'bag_id');
    }


    public function roleUser()
    {
        return $this->belongsTo(FranchiseRoleUser::class, 'franchise_role_users_id');
    }


    public function cms()
    {
        return $this->belongsTo(CMS::class, 'cms_id');
    }

    public function franchise()
    {
        return $this->belongsTo(Franchise::class, 'franchise_id');
    }

    public function deliveryBoy()
    {
        return $this->belongsTo(DeliveryBoy::class, 'delivery_boy_id');
    }

    public function cancelDelivery()
    {
        return $this->morphOne(CancelDelivery::class, 'parcel');
    }

    public function franchiseNotifications(): MorphMany
    {
        return $this->morphMany(FranchiseNotification::class, 'parcel', 'service_type', 'parcel_id');
    }

    public function userNotifications(): MorphMany
    {
        return $this->morphMany(UserNotification::class, 'parcel', 'service_type', 'parcel_id');
    }

    public function deliveryBoyNotifications(): MorphMany
    {
        return $this->morphMany(DeliveryBoyNotification::class, 'parcel', 'service_type', 'parcel_id');
    }
    
}
