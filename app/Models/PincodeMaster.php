<?php
// app/Models/PincodeMaster.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PincodeMaster extends Model
{
    use HasFactory;

    protected $table = 'pincode_master';
    
    protected $fillable = [
        'origin_pincode',
        'destination_pincode',
        'distance_km'
    ];
    
    protected $casts = [
        'distance_km' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    /**
     * Check if pincode exists in master table
     */
    public static function pincodeExists($pincode)
    {
        return self::where('origin_pincode', $pincode)
            ->orWhere('destination_pincode', $pincode)
            ->exists();
    }
    
    /**
     * Get distance between two pincodes
     */
    public static function getDistance($origin, $destination)
    {
        $record = self::where('origin_pincode', $origin)
            ->where('destination_pincode', $destination)
            ->first();
        
        return $record ? $record->distance_km : null;
    }
}