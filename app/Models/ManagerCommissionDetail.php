<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagerCommissionDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'commission_id',
        'servicetype',
        'amount',
        'commission',
        'type',
        'payment_method',
        'created_at',
        'updated_at'
    ];
}