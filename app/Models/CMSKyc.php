<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CMSKyc extends Model
{
    use HasFactory;
    protected $table = 'cms_kycs';
    protected $fillable = [
        'cms_id',
        'adhar_card',
        'pan_card',
        'ifsc_code',
        'bank_name',
        'branch_name',
        'account_number',
        'adhar_front_img',
        'adhar_back_img',
        'cheque_img',
        'photo',
        'cms_img',
        'status',
        'applied_at',
        'approved_at',
        'rejected_at',
        'reject_reason',
    ];

    public function cms()
    {
        return $this->belongsTo(CMS::class,'cms_id');
    }

}
