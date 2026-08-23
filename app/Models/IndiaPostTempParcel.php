<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndiaPostTempParcel extends Model
{
    protected $table = 'india_post_temp_parcels';
    
    protected $fillable = [
        'session_key',
        'franchise_id',
        'row_data',
        'calculated_weight',
        'calculated_price',
        'gst_amount',
        'net_amount',
        'barcode_option',
        'status',
        'error_reason'
    ];
    
    protected $casts = [
        'row_data' => 'array',
    ];
}