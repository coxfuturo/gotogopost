<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasFactory, HasRoles;
    protected $table = 'admins';
    protected $fillable = [
        'name',
        'phone',
        'email',
        'image',
        'password',
        'status',
    ];

    public const GOTOGO_POST_SPEED = "Super Speed Packet";
    public const GOTOGO_POST_BUSINESS = 'Post Express Parcel';
    public const GOTOGO_POST_REGISTERED = 'Post Secure Parcel-';
    public const INDIA_POST_SPEED = 'SP-Document Domestic';
    public const INDIA_POST_BUSINESS = 'SP-Parcel Domestic';
    public const INDIA_POST_REGISTERED = 'SP-Buisness Parcel';
    public const E2E = 'E2E';
    public const E2H = 'E2H';


    // dd(\App\Models\Admin::GOTOGO_POST_SPEED);
    // dd(\App\Models\Admin::GOTOGO_POST_BUSINESS);
    // dd(\App\Models\Admin::GOTOGO_POST_REGISTERED);
    // dd(\App\Models\Admin::INDIA_POST_SPEED);
    // dd(\App\Models\Admin::INDIA_POST_BUSINESS);
    // dd(\App\Models\Admin::E2E);
    // dd(\App\Models\Admin::E2H);


    // {{\App\Models\Admin::GOTOGO_POST_SPEED}}
    // {{\App\Models\Admin::GOTOGO_POST_BUSINESS}}
    // {{\App\Models\Admin::GOTOGO_POST_REGISTERED}}
    // {{\App\Models\Admin::INDIA_POST_SPEED}}
    // {{\App\Models\Admin::INDIA_POST_BUSINESS}}
    // {{\App\Models\Admin::E2E}}
    // {{\App\Models\Admin::E2H}}

}
