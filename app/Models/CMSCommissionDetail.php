<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CMSCommissionDetail extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function cms()
    {
        return $this->belongsTo(CMS::class);
    }
}
