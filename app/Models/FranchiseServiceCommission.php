<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FranchiseServiceCommission extends Model
{
    use HasFactory;

    protected $fillable = [
        'franchise_id',
        'service_type',
        'commission_rate',
        'is_active',
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function franchise()
    {
        return $this->belongsTo(Franchise::class);
    }

    public static function getActiveRate($franchiseId, $serviceType)
    {
        $setting = self::where('franchise_id', $franchiseId)
            ->where('service_type', $serviceType)
            ->where('is_active', true)
            ->first();

        return $setting ? (float) $setting->commission_rate : 0;
    }
}