<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPHKyc extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'p_p_h_kycs';

    public function pph()
    {
        return $this->belongsTo(PPH::class, 'pph_id');
    }
}
