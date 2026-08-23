<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GotogoLink extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function franchise()
    {
        return $this->belongsTo(Franchise::class, 'franchise_no', 'franchise_no');
    }
    public function cms()
    {
        return $this->belongsTo(CMS::class, 'cms_no', 'cms_no');
    }
    public function pph()
    {
        return $this->belongsTo(PPH::class, 'pph_no', 'pph_no');
    }
}
