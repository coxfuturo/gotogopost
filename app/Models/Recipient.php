<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipient extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function franchise()
    {
        return $this->belongsTo(Franchise::class, 'destination_franchise_id', 'id');
    }
}
