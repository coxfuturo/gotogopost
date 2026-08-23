<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FranchiseBarcodes extends Model
{
    use HasFactory;

    protected $guarded = [];

    
    public function series()
    {
        return $this->belongsTo(FranchiseBarcodeSeries::class, 'franchise_barcodeseries_id');
    }
}
