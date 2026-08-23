<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MManagerKyc extends Model
{
    use HasFactory;

    protected $fillable = [
        'm_manager_id',
        'collage',
        'adhar_card',
        'pan_card',
        'ifsc_code',
        'bank_name',
        'branch_name',
        'account_number',
        'adhar_front_img',
        'adhar_back_img',
        'cheque_img',
        'pan_img',
        'resume',
        'photo',
        'other_document',
        'video_kyc',
        'applied_at',
        'approved_at',
        'rejected_at',
        'reject_reason',
        'marksheetType',
        'grade',
        'percentage',
        'marksheet',
        'experience',
        'start_date',
        'end_date',
       
    ];

    public function manager()
    {
        return $this->belongsTo(MManager::class, 'm_manager_id');
    }
}
