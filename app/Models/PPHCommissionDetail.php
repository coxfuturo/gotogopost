<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPHCommissionDetail extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function pph()
    {
        return $this->belongsTo(PPH::class);
    }
}
