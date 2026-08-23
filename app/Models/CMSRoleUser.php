<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class CMSRoleUser extends Authenticatable 
{
    use HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'image',
        'password',
        'status',
    ];

    public function getAuthIdentifierName()
    {
        return 'id'; 
    }
}
