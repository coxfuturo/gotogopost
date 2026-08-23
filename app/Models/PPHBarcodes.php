<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPHBarcodes extends Model
{
    use HasFactory;

    public function series()
    {
        return $this->belongsTo(PPHBarcodeSeries::class, 'pph_barcodeseries_id');
    }
}
