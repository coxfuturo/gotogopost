<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GotogoSpeedPostParcel;
use DB;

class FranchiseBag extends Model
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


    protected static $serviceCode = [
        self::SERVICE_TYPE_GOTO_POST_SPEED => 'S',
        self::SERVICE_TYPE_GOTO_POST_SUPERFAST => 'F',
        self::SERVICE_TYPE_GOTO_POST_BUSINESS_PARCEL => 'B',
        self::SERVICE_TYPE_GOTO_POST_REGISTERED => 'R',
        self::SERVICE_TYPE_INDIA_POST_SPEED => 'D',
        self::SERVICE_TYPE_INDIA_POST_BUSINESS => 'N',
        self::SERVICE_TYPE_INDIA_POST_REGISTERED => 'P',
    ];

    protected static $serviceModel = [
        self::SERVICE_TYPE_GOTO_POST_SPEED => \App\Models\GotogoSpeedPostParcel::class,
        self::SERVICE_TYPE_GOTO_POST_BUSINESS_PARCEL => \App\Models\GotogoBusinessParcel::class,
        self::SERVICE_TYPE_GOTO_POST_REGISTERED => \App\Models\GotogoRegisteredParcel::class,
        self::SERVICE_TYPE_INDIA_POST_SPEED => \App\Models\IndiaPostSpeedPostParcel::class,
        self::SERVICE_TYPE_INDIA_POST_BUSINESS => \App\Models\IndiaPostBusinessParcel::class,
    ];

    protected static $trackingModel = [
        self::SERVICE_TYPE_GOTO_POST_SPEED => \App\Models\GotogoSpeedPostTrackOrder::class,
        self::SERVICE_TYPE_GOTO_POST_BUSINESS_PARCEL => \App\Models\GotogoBusinessTrackOrder::class,
        self::SERVICE_TYPE_GOTO_POST_REGISTERED => \App\Models\GotogoRegisteredTrackOrder::class,
        self::SERVICE_TYPE_INDIA_POST_SPEED => \App\Models\IndiaPostSpeedPostTrackOrder::class,
        self::SERVICE_TYPE_INDIA_POST_BUSINESS => \App\Models\IndiaPostBusinessTrackOrder::class,
    ];


    protected static $serviceType = [
        self::SERVICE_TYPE_GOTO_POST_SPEED => 'Super Speed Packet',
        self::SERVICE_TYPE_GOTO_POST_SUPERFAST => 'Post SuperFast',
        self::SERVICE_TYPE_GOTO_POST_BUSINESS_PARCEL => 'Post Express Package',
        self::SERVICE_TYPE_GOTO_POST_REGISTERED => 'Post Secure Packet',
        self::SERVICE_TYPE_INDIA_POST_SPEED => 'Post Speed Packet/Parcel',
        self::SERVICE_TYPE_INDIA_POST_BUSINESS => 'Post Business Package',
        self::SERVICE_TYPE_INDIA_POST_REGISTERED => 'India Post Registered',
        self::E2E => 'E2E',
        self::E2H => 'E2H',
    ];

    public static function getServiceType($id)
    {

        return self::$serviceType[$id] ?? null;
    }

    public static function getServiceModel($id)
    {

        return self::$serviceModel[$id] ?? null;
    }


    public static function getServiceTypeFromModel(string $modelName): ?int
    {
        return array_search($modelName, self::$serviceModel, true) ?: null;
    }

    public static function getTrackingModel($id)
    {
        return self::$trackingModel[$id] ?? null;
    }

    public static function getTrackingModelByBarcode($id)
    {
        // Pehle Mail table me check karein
        if (\App\Models\Mail::where('mail_code', $id)->exists()) {
            return \App\Models\E2HTrackOrder::class;
        }

        // Temporary table create karo (session-based, auto-delete ho jayegi)
        DB::statement("CREATE TEMPORARY TABLE IF NOT EXISTS temp_tracking_models (
            barcode VARCHAR(255) UNIQUE,
            model_name VARCHAR(255)
        )");

        // Saare tracking models loop karke temporary table me insert karo
        foreach (self::$trackingModel as $serviceType => $model) {
            if ($model::where('barcode_no', $id)->exists()) { // Sirf check karega, data load nahi karega
                DB::table('temp_tracking_models')->updateOrInsert(
                    ['barcode' => $id],
                    ['model_name' => $model]
                );
            }
        }

        // Temporary table se barcode search karo
        $record = DB::table('temp_tracking_models')->where('barcode', $id)->first();

        if ($record) {
            return $record->model_name; // Sirf tracking model return karega
        }

        return null; // Koi match nahi mila
    }



    public static function getBookingModelByBarcode($barcode)
    {

        // Pehle Mail table me check karein
        if (\App\Models\Mail::where('mail_code', $barcode)->exists()) {
            return \App\Models\Mail::class;
        }

        // Temporary table create karo (session-based hoti hai, auto-delete ho jayegi)
        DB::statement("CREATE TEMPORARY TABLE IF NOT EXISTS temp_parcel_barcodes (
        barcode VARCHAR(255) UNIQUE,
        model_name VARCHAR(255)
    )");

        // Saare models se barcode nikal ke temporary table me insert karo
        foreach (self::$serviceModel as $serviceType => $model) {
            if ($model::where('barcode_no', $barcode)->exists()) { // Sirf check karega, data fetch nahi karega
                DB::table('temp_parcel_barcodes')->updateOrInsert(
                    ['barcode' => $barcode],
                    ['model_name' => $model]
                );
            }
        }

        // Temporary table se barcode search karo
        $record = DB::table('temp_parcel_barcodes')->where('barcode', $barcode)->first();

        if ($record) {
            return $record->model_name;
        }

        return null;
    }


    public static function getBookingModelByNumber($number)
    {
        // Ensure the temporary table has the necessary columns
        DB::statement("CREATE TEMPORARY TABLE IF NOT EXISTS temp_parcel_barcodes (
        pickup_mobile VARCHAR(255),
        model_name VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

        // Check in all service models and insert if found
        foreach (self::$serviceModel as $serviceType => $model) {
            $existingRecord = $model::where('pickup_mobile', $number)
                ->latest('created_at')
                ->first();

            if ($existingRecord) {
                DB::table('temp_parcel_barcodes')->insert([
                    'pickup_mobile' => $number,
                    'model_name' => $model,
                    'created_at' => $existingRecord->created_at ?? now()
                ]);
            }
        }

        // // Check Recipient model and insert if found
        $recipient = \App\Models\Recipient::where('recipient_phone', $number)
            ->latest('created_at')
            ->first();

        if ($recipient) {
            DB::table('temp_parcel_barcodes')->insert([
                'pickup_mobile' => $number,
                'model_name' =>  \App\Models\Recipient::class,
                'created_at' => $existingRecord->created_at ?? now()
            ]);
        }

        // Retrieve the latest record from the temp table
        $record = DB::table('temp_parcel_barcodes')
            ->where('pickup_mobile', $number)
            ->latest('created_at')
            ->first();

        return $record ? $record->model_name : null;
    }


    public function cms()
    {
        return $this->belongsTo(CMS::class, 'cms_id');
    }

    public function deliveryBoy()
    {
        return $this->belongsTo(DeliveryBoy::class, 'delivery_boy_id');
    }

    public function franchise()
    {
        return $this->belongsTo(Franchise::class, 'franchise_id');
    }
}
