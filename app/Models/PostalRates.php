<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostalRates extends Model
{
    use HasFactory;
    protected $guarded = [];

    public const SERVICE_TYPE_INDIAN_POSTAL_RATES = 1;
    public const SERVICE_TYPE_GOTOTO_POSTAL_RATES = 2;
    public const SERVICE_TYPE_INDIAN_REGISTERED_RATES = 3;
    public const SERVICE_TYPE_GOTOGO_REGISTERED_RATES = 4;


    protected static $serviceType = [
        self::SERVICE_TYPE_INDIAN_POSTAL_RATES => 'Indian Postal Rates',
        self::SERVICE_TYPE_GOTOTO_POSTAL_RATES => 'Gotogo Postal Rates',
        self::SERVICE_TYPE_INDIAN_REGISTERED_RATES => 'Indian Registered Rates',
        self::SERVICE_TYPE_GOTOGO_REGISTERED_RATES => 'Gotogo Registered Rates',
    ];


    public static function getServiceType($id)
    {
        return self::$serviceType[$id] ?? null;
    }
}
