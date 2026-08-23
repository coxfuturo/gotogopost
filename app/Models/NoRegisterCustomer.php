<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Auth;


    class NoRegisterCustomer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'type',    
        'franchise_id',   
        'market_id',   
        'name',      
        'phone',    
        'gst_no',    
        'email',   
        'image',     
        'email_verified_at',   
        'pincode',  
        'city',   
        'state',   
        'address',   
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
    ];
  
    public function manager()
    {
        return $this->belongsTo(MManager::class, 'market_id');
    }

     public function franchise()
    {
        return $this->belongsTo(Franchise::class, 'franchise_id');
    }

    public function gotogopost()
    {
        return $this->hasOne(GotogoSpeedPostParcel::class, 'no_r_customer_id');
    }

    public function gotoBusiness()
    {
        return $this->hasOne(GotogoBusinessParcel::class, 'no_r_customer_id');
    }

    public function postsecure()
    {
        return $this->hasOne(GotogoRegisteredParcel::class, 'no_r_customer_id');
    }

    public function indiaPostBusiness()
    {
        return $this->hasOne(IndiaPostBusinessParcel::class, 'no_r_customer_id');
    }

    public function indiaPostSpeed()
    {
        return $this->hasOne(IndiaPostSpeedPostParcel::class, 'no_r_customer_id');
    }

}

