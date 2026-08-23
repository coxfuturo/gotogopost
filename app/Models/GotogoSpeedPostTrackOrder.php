<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GotogoSpeedPostTrackOrder extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function cms()
    {
        return $this->belongsTo(CMS::class);
    }


    public function franchise()
    {
        return $this->belongsTo(Franchise::class);
    }

    // public function pph()
    // {
    //     return $this->belongsTo(PPH::class, 'pph_id');
    // }
    // 🔹 Source Franchise Relation
    public function sourceFranchise()
    {
        return $this->belongsTo(Franchise::class, 'source_franchise_id');
    }

    // 🔹 Source CMS Relation
    public function sourceCMS()
    {
        return $this->belongsTo(CMS::class, 'source_cms_id');
    }

    // 🔹 PPH Relation
    public function pph()
    {
        return $this->belongsTo(PPH::class, 'pph_id');
    }

    // 🔹 Destination CMS Relation
    public function destinationCMS()
    {
        return $this->belongsTo(CMS::class, 'destination_cms_id');
    }

    // 🔹 Destination Franchise Relation
    public function destinationFranchise()
    {
        return $this->belongsTo(Franchise::class, 'destination_franchise_id');
    }

    // 🔹 Delivery Boy Relation
    public function deliveryBoy()
    {
        return $this->belongsTo(User::class, 'delivery_boy_id'); // Agar delivery boy `users` table me hai
    }
}


