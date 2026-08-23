<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory;
    protected $guarded = [];


    protected static $serviceType = [
        'franchise' => 'COMMISSION PAYABLE TO THE ASSOCIATE ON DOMESTIC SHIPMENT',
        'cph' => 'COMMISSION PAYABLE TO THE CPH ON DOMESTIC SHIPMENT',
        'pph' => 'COMMISSION PAYABLE TO THE PPH ON DOMESTIC SHIPMENT',
        'delivery' => 'COMMISSION PAYABLE TO THE DELIVERY ON DOMESTIC SHIPMENT',
    ];


    public static function getMemberType($id)
    {
        return self::$serviceType[$id] ?? null;
    }
}
