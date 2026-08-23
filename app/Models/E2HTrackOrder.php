<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class E2HTrackOrder extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function sourceFranchise()
    {
        return $this->belongsTo(Franchise::class, 'source_franchise_id');
    }

    public function destinationFranchise()
    {
        return $this->belongsTo(Franchise::class, 'destination_franchise_id');
    }

    public function cms()
    {
        return $this->belongsTo(CMS::class, 'cms_id');
    }
    public function pph()
    {
        return $this->belongsTo(PPH::class, 'pph_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function mail()
    {
        return $this->belongsTo(Mail::class, 'mail_id');
    }
    public function deliveryBoy()
    {
        return $this->belongsTo(DeliveryBoy::class, 'delivery_boy_id');
    }

    public function recipients()
    {
        return $this->hasMany(Recipient::class, 'mail_id');
    }
}
