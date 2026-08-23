<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CMSBarcodes extends Model
{
    use HasFactory;


    public function series()
    {
        return $this->belongsTo(CMSBarcodeSeries::class, 'cms_barcodeseries_id');
    }
}
