<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class FranchiseRoleUser extends Authenticatable
{
    use HasFactory, HasRoles;

    protected $guarded = [];


    public function franchise()
    {
        return $this->belongsTo(Franchise::class, 'franchise_id');
    }
}
