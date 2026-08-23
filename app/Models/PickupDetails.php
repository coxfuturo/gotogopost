<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class PickupDetails extends Model
{
    use HasFactory;
    protected $fillable = [
        'franchise_id',
        'cms_id',
        'name',
        'email',
        'phone',
        'pincode',
        'city',
        'state',
        'status',
        'gst_no',
        'deliveryboy_id',
        'address',
    ];

    public function deliveryBoy()
    {
        return $this->belongsTo(DeliveryBoy::class, 'deliveryboy_id');
    }

    public function deliveryBoyNotifications(): MorphMany
    {
        return $this->morphMany(UserNotification::class, 'parcel', 'service_type', 'parcel_id');
    }

    public function frenchise()
    {
        return $this->belongsTo(Franchise::class, 'franchise_id');
    }
}
