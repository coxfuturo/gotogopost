<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CancelDelivery extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function parcel()
    {
        return $this->morphTo(__FUNCTION__, 'parcel_type', 'parcel_id');
    }

    public function cancelledBy()
    {
        return $this->belongsTo(DeliveryBoy::class, 'cancelled_by', 'id');
    }
}
