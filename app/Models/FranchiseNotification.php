<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FranchiseNotification extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function parcel(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'service_type', 'parcel_id');
    }
}
