<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndiaPostLink extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function franchise()
    {
        return $this->belongsTo(Franchise::class, 'franchise_no', 'franchise_no');
    }
}
