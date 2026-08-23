<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mail extends Model
{
    use HasFactory;
    protected $guarded = [];



    public const SERVICE_TYPE_GOTO_POST_SPEED = 1;
    public const SERVICE_TYPE_GOTO_POST_SUPERFAST = 2;
    public const SERVICE_TYPE_GOTO_POST_BUSINESS_PARCEL = 3;
    public const SERVICE_TYPE_GOTO_POST_REGISTERED = 4;
    public const SERVICE_TYPE_INDIA_POST_SPEED = 5;
    public const SERVICE_TYPE_INDIA_POST_BUSINESS = 6;
    public const SERVICE_TYPE_INDIA_POST_REGISTERED = 7;
    public const E2E = 8;
    public const E2H = 9;


    protected static $serviceType = [
        self::SERVICE_TYPE_GOTO_POST_SPEED => 'GOTOGO Post Speed',
        self::SERVICE_TYPE_GOTO_POST_SUPERFAST => 'GOTOGO Post SuperFast',
        self::SERVICE_TYPE_GOTO_POST_BUSINESS_PARCEL => 'GOTOGO Post Business Parcel',
        self::SERVICE_TYPE_GOTO_POST_REGISTERED => 'GOTOGO Post Registered',
        self::SERVICE_TYPE_INDIA_POST_SPEED => 'India Post Speed',
        self::SERVICE_TYPE_INDIA_POST_BUSINESS => 'India Post Business',
        self::SERVICE_TYPE_INDIA_POST_REGISTERED => 'India Post Registered',
        self::E2E => 'E2E',
        self::E2H => 'E2H',
    ];

    public static function getServiceType($id)
    {

        return self::$serviceType[$id] ?? null;
    }

    public function recipients()
    {
        return $this->hasMany(Recipient::class, 'mail_id');
    }

    public function attachments()
    {
        return $this->hasMany(MailAttachment::class, 'mail_id');
    }
    public function franchise()
    {
        return $this->belongsTo(Franchise::class, 'franchise_id', 'id');
    }

    public function cms()
    {
        return $this->belongsTo(CMS::class, 'cms_id');
    }
    public function pph()
    {
        return $this->belongsTo(PPH::class, 'pph_id');
    }

    public function deliveryBoy()
    {
        return $this->belongsTo(DeliveryBoy::class, 'delivery_boy_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function cancelDelivery()
    {
        return $this->morphOne(CancelDelivery::class, 'parcel');
    }


    // In your Mail model
    public static function getTotalPaymentForFranchise($franchiseId)
    {
        return self::where('franchise_id', $franchiseId) // Filter by franchise_id
            ->withSum('attachments', 'payment_amount') // Sum the payment_amount from related attachments
            ->get()
            ->sum('attachments_sum_payment_amount'); // Get the total sum
    }

    public static function getTotalPaymentForCMS($CMSId)
    {
        return self::where('cms_id', $CMSId)
            ->withSum('attachments', 'payment_amount')
            ->get()
            ->sum('attachments_sum_payment_amount');
    }

    public static function getTotalPaymentForPPH($PPHId)
    {
        return self::where('pph_id', $PPHId)
            ->withSum('attachments', 'payment_amount')
            ->get()
            ->sum('attachments_sum_payment_amount');
    }


    public static function getUniqueCode()
    {
        do {
            $code = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 10));
            $exists = self::where('mail_code', $code)->exists(); // Check if code exists in database
        } while ($exists);

        return "E" . $code;
    }
}
