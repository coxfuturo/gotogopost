<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GotogoPostalRates extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const SERVICE_TYPE_GOTOTO_SPEED = 1;
    public const SERVICE_TYPE_GOTOTO_BUSINESS = 3;
    public const SERVICE_TYPE_GOTOGO_REGISTERED_RATES = 4;
    public const SERVICE_TYPE_INDIAN_POSTAL_RATES = 5;

    protected static $serviceType = [
        self::SERVICE_TYPE_INDIAN_POSTAL_RATES => 'Indian Postal Rates',
        self::SERVICE_TYPE_GOTOTO_SPEED => 'Gototo Speed Packet',
        self::SERVICE_TYPE_GOTOTO_BUSINESS => 'Gototo Business Package',
        self::SERVICE_TYPE_GOTOGO_REGISTERED_RATES => 'Gotogo Legal Document Delivery',
    ];

    public static function getServiceType($id)
    {
        return self::$serviceType[$id] ?? null;
    }
}
