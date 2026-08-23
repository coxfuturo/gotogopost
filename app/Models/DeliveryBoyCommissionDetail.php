<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryBoyCommissionDetail extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function DeliveryBoy()
    {
        return $this->belongsTo(DeliveryBoy::class);
    }
}
