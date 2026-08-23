<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class CodGotogoBusinessParcel extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'e_customers';

    protected $fillable = [
        'name',
        'phone',
        'gst_no',
        'email',
        'pincode',
        'city',
        'state',
        'address',
        'status',
        'image',
        'password',
    ];

    protected $hidden = [
        'password',
    ];
}

