<?php



namespace App\Http\Controllers\deliveryBoy;



use App\Http\Controllers\Controller;

use App\Models\GotogoSuperFastParcel;



use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;



use DB;



use Carbon\Carbon;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;



class GotogoSuperFastController extends Controller

{


















    // public function index(Request $request)

    // {



    // Schema::table('gotogo_speed_post_parcels', function (Blueprint $table) {
    //     $table->renameColumn('franchise_role_user_id', 'franchise_role_users_id');
    // });
    // Schema::table('gotogo_speed_post_parcels', function (Blueprint $table) {
    //     $table->renameColumn('franchise_role_user_id', 'franchise_role_users_id');
    // });

    //     // Schema::table('gotogo_super_fast_parcels', function (Blueprint $table) {
    //     //     $table->renameColumn('franchise_role_user_id', 'franchise_role_users_id');
    //     // });

    //     // Schema::table('gotogo_business_parcels', function (Blueprint $table) {
    //     //     $table->renameColumn('franchise_role_user_id', 'franchise_role_users_id');
    //     // });

    //     // Schema::table('gotogo_registered_parcels', function (Blueprint $table) {
    //     //     $table->renameColumn('franchise_role_user_id', 'franchise_role_users_id');
    //     // });

    //     // Schema::table('india_post_registered_parcels', function (Blueprint $table) {
    //     //     $table->renameColumn('franchise_role_user_id', 'franchise_role_users_id');
    //     // });

    //     // Schema::table('india_post_speed_post_parcels', function (Blueprint $table) {
    //     //     $table->renameColumn('franchise_role_user_id', 'franchise_role_users_id');
    //     // });

    //     // Schema::table('india_post_business_parcels', function (Blueprint $table) {
    //     //     $table->renameColumn('franchise_role_user_id', 'franchise_role_users_id');
    //     // });











    //     // Schema::table('gotogo_speed_post_parcels', function (Blueprint $table) {
    //     //     $table->date('scid_file_upload_date')
    //     //         ->nullable()
    //     //         ->default(null);

    //     //     $table->date('dcid_file_upload_date')
    //     //         ->nullable()
    //     //         ->default(null);

    //     //     $table->date('dfid_file_upload_date')
    //     //         ->nullable()
    //     //         ->default(null);
    //     // });


    //     // Schema::table('gotogo_super_fast_parcels', function (Blueprint $table) {
    //     //     $table->date('scid_file_upload_date')
    //     //         ->nullable()
    //     //         ->default(null);

    //     //     $table->date('dcid_file_upload_date')
    //     //         ->nullable()
    //     //         ->default(null);

    //     //     $table->date('dfid_file_upload_date')
    //     //         ->nullable()
    //     //         ->default(null);
    //     // });

    //     // Schema::table('gotogo_business_parcels', function (Blueprint $table) {
    //     //     $table->date('scid_file_upload_date')
    //     //         ->nullable()
    //     //         ->default(null);

    //     //     $table->date('dcid_file_upload_date')
    //     //         ->nullable()
    //     //         ->default(null);

    //     //     $table->date('dfid_file_upload_date')
    //     //         ->nullable()
    //     //         ->default(null);
    //     // });

    //     // Schema::table('gotogo_registered_parcels', function (Blueprint $table) {
    //     //     $table->date('scid_file_upload_date')
    //     //         ->nullable()
    //     //         ->default(null);

    //     //     $table->date('dcid_file_upload_date')
    //     //         ->nullable()
    //     //         ->default(null);

    //     //     $table->date('dfid_file_upload_date')
    //     //         ->nullable()
    //     //         ->default(null);
    //     // });

    //     // Schema::table('india_post_registered_parcels', function (Blueprint $table) {
    //     //     $table->date('scid_file_upload_date')
    //     //         ->nullable()
    //     //         ->default(null);

    //     //     $table->date('dcid_file_upload_date')
    //     //         ->nullable()
    //     //         ->default(null);

    //     //     $table->date('dfid_file_upload_date')
    //     //         ->nullable()
    //     //         ->default(null);
    //     // });
    //     // Schema::table('india_post_speed_post_parcels', function (Blueprint $table) {
    //     //     $table->date('scid_file_upload_date')
    //     //         ->nullable()
    //     //         ->default(null);

    //     //     $table->date('dcid_file_upload_date')
    //     //         ->nullable()
    //     //         ->default(null);

    //     //     $table->date('dfid_file_upload_date')
    //     //         ->nullable()
    //     //         ->default(null);
    //     // });
    //     // Schema::table('india_post_business_parcels', function (Blueprint $table) {
    //     //     $table->date('scid_file_upload_date')
    //     //         ->nullable()
    //     //         ->default(null);

    //     //     $table->date('dcid_file_upload_date')
    //     //         ->nullable()
    //     //         ->default(null);

    //     //     $table->date('dfid_file_upload_date')
    //     //         ->nullable()
    //     //         ->default(null);
    //     // });


    //     // dd('done created');








    // Schema::table('mail_attachments', function (Blueprint $table) {
    //     $table->string('file_name')->nullable();
    //     $table->string('file_size')->nullable();
    // });

    // Schema::create('mail_attachments', function (Blueprint $table) {
    //     $table->id();
    //     $table->foreignId('mail_id')->constrained('mails')->onDelete('cascade');
    //     $table->string('attachment');

    //     $table->timestamps();
    // });


    // dd('created');

    // Schema::table('gotogo_speed_post_parcels', function (Blueprint $table) {
    //     $table->decimal('fuel_charge', 8, 2)->nullable()->default(0)->after('payment_method');
    //     $table->decimal('pickup_charge', 8, 2)->nullable()->default(0)->after('fuel_charge');
    //     $table->decimal('other_service_charge', 8, 2)->nullable()->default(0)->after('pickup_charge');
    // });


    // Schema::table('gotogo_super_fast_parcels', function (Blueprint $table) {
    //     $table->decimal('fuel_charge', 8, 2)->nullable()->default(0)->after('payment_method');
    //     $table->decimal('pickup_charge', 8, 2)->nullable()->default(0)->after('fuel_charge');
    //     $table->decimal('other_service_charge', 8, 2)->nullable()->default(0)->after('pickup_charge');
    // });

    // Schema::table('gotogo_business_parcels', function (Blueprint $table) {
    //     $table->decimal('fuel_charge', 8, 2)->nullable()->default(0)->after('payment_method');
    //     $table->decimal('pickup_charge', 8, 2)->nullable()->default(0)->after('fuel_charge');
    //     $table->decimal('other_service_charge', 8, 2)->nullable()->default(0)->after('pickup_charge');
    // });

    // Schema::table('gotogo_registered_parcels', function (Blueprint $table) {
    //     $table->decimal('fuel_charge', 8, 2)->nullable()->default(0)->after('payment_method');
    //     $table->decimal('pickup_charge', 8, 2)->nullable()->default(0)->after('fuel_charge');
    //     $table->decimal('other_service_charge', 8, 2)->nullable()->default(0)->after('pickup_charge');
    // });

    // Schema::table('india_post_registered_parcels', function (Blueprint $table) {
    //     $table->decimal('fuel_charge', 8, 2)->nullable()->default(0)->after('payment_method');
    //     $table->decimal('pickup_charge', 8, 2)->nullable()->default(0)->after('fuel_charge');
    //     $table->decimal('other_service_charge', 8, 2)->nullable()->default(0)->after('pickup_charge');
    // });
    // Schema::table('india_post_speed_post_parcels', function (Blueprint $table) {
    //     $table->decimal('fuel_charge', 8, 2)->nullable()->default(0)->after('payment_method');
    //     $table->decimal('pickup_charge', 8, 2)->nullable()->default(0)->after('fuel_charge');
    //     $table->decimal('other_service_charge', 8, 2)->nullable()->default(0)->after('pickup_charge');
    // });
    // Schema::table('india_post_business_parcels', function (Blueprint $table) {
    //     $table->decimal('fuel_charge', 8, 2)->nullable()->default(0)->after('payment_method');
    //     $table->decimal('pickup_charge', 8, 2)->nullable()->default(0)->after('fuel_charge');
    //     $table->decimal('other_service_charge', 8, 2)->nullable()->default(0)->after('pickup_charge');
    // });


    // dd('done created');


    // Schema::table('mails', function (Blueprint $table) {
    //     $table->string('file_name')->nullable();
    //     $table->string('file_size')->nullable();
    // });

    // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    // Schema::dropIfExists('mail_attachments');
    // DB::statement('SET FOREIGN_KEY_CHECKS=1;');


    // Schema::table('mails', function (Blueprint $table) {
    //     $table->dropForeign('mails_franchise_id_foreign');
    // });


    // Schema::create('mails', function (Blueprint $table) {
    //     $table->id();
    //     $table->string('service_type');
    //     $table->foreignId('franchise_id')->nullable()->constrained('franchises')->onDelete('set null');
    //     $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
    //     $table->string('subject')->nullable();
    //     $table->string('star')->nullable();
    //     $table->string('read')->nullable();
    //     $table->text('body')->nullable();
    //     $table->timestamps();
    // });

    // dd('crearted');
    // Schema::create('recipients', function (Blueprint $table) {
    //     $table->id();
    //     $table->foreignId('mail_id')->nullable()->constrained('mails')->onDelete('set null');
    //     $table->foreignId('destination_franchise_id')->nullable()->constrained('franchises')->onDelete('set null');
    //     $table->string('recipient_phone')->nullable();
    //     $table->string('recipient_address')->nullable();
    //     $table->string('status')->default('0');
    //     $table->timestamps();
    // });

    // dd('crearted 999999999');
    // Schema::create('mail_attachments', function (Blueprint $table) {
    //     $table->id();
    //     $table->foreignId('recipient_id')->nullable()->constrained('recipients')->onDelete('set null');
    //     $table->foreignId('mail_id')->nullable()->constrained('mails')->onDelete('set null');
    //     $table->string('file_name');
    //     $table->string('file_path');
    //     $table->string('file_size');
    //     $table->string('payment_amount');
    //     $table->timestamps();
    // });

    // dd('jjjjjj');




    // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    //     Schema::dropIfExists('cancel_deliveries');
    //     DB::statement('SET FOREIGN_KEY_CHECKS=1;');


    //     Schema::create('cancel_deliveries', function (Blueprint $table) {
    //         $table->id();
    //         $table->string('parcel_type');
    //         $table->text('cancel_reason');
    //         $table->string('parcel_id');
    //         $table->timestamps();
    //     });

    //     dd('cone');




    // }




    // Schema::table('gotogo_speed_post_parcels', function (Blueprint $table) {
    //     $table->foreignId('source_pph_bag_id')->nullable()->default(null)->constrained('p_p_h_bags');
    // });


    // Schema::table('gotogo_super_fast_parcels', function (Blueprint $table) {
    //     $table->foreignId('source_pph_bag_id')->nullable()->default(null)->constrained('p_p_h_bags');
    // });

    // Schema::table('gotogo_business_parcels', function (Blueprint $table) {
    //     $table->foreignId('source_pph_bag_id')->nullable()->default(null)->constrained('p_p_h_bags');
    // });

    // Schema::table('gotogo_registered_parcels', function (Blueprint $table) {
    //     $table->foreignId('source_pph_bag_id')->nullable()->default(null)->constrained('p_p_h_bags');
    // });

    // Schema::table('india_post_registered_parcels', function (Blueprint $table) {
    //     $table->foreignId('source_pph_bag_id')->nullable()->default(null)->constrained('p_p_h_bags');
    // });
    // Schema::table('india_post_speed_post_parcels', function (Blueprint $table) {
    //     $table->foreignId('source_pph_bag_id')->nullable()->default(null)->constrained('p_p_h_bags');
    // });
    // Schema::table('india_post_business_parcels', function (Blueprint $table) {
    //     $table->foreignId('source_pph_bag_id')->nullable()->default(null)->constrained('p_p_h_bags');
    // });




    //     Schema::table('p_p_h_s_bags', function (Blueprint $table) {

    //         $table->renameColumn('pph_bag_id', 'pph_id');
    //    //     // $table->foreignId('pph_bag_id')->nullable()->default(null)->constrained('p_p_h_bags');
    //     });


    //     dd('kkkk');
    // Schema::table('c_m_s_bags', function (Blueprint $table) {


    //     // Add the new pph_id column with a foreign key constraint
    //     $table->foreignId('pph_id')
    //         ->nullable()
    //         ->default(null)
    //         ->constrained('p_p_h_s');
    // });


    // dd('done');


    // Schema::table('gotogo_speed_post_parcels', function (Blueprint $table) {
    //     $table->date('spid_file_upload_date')
    //         ->nullable()
    //         ->default(null);
    //     $table->foreignId('spid_forfile_upload')
    //         ->nullable()
    //         ->default(null)
    //         ->constrained('p_p_h_s');
    // });


    // Schema::table('gotogo_super_fast_parcels', function (Blueprint $table) {
    //      $table->date('spid_file_upload_date')
    //         ->nullable()
    //         ->default(null);
    //     $table->foreignId('spid_forfile_upload')
    //         ->nullable()
    //         ->default(null)
    //         ->constrained('p_p_h_s');
    // });

    // Schema::table('gotogo_business_parcels', function (Blueprint $table) {
    //      $table->date('spid_file_upload_date')
    //         ->nullable()
    //         ->default(null);
    //     $table->foreignId('spid_forfile_upload')
    //         ->nullable()
    //         ->default(null)
    //         ->constrained('p_p_h_s');
    // });

    // Schema::table('gotogo_registered_parcels', function (Blueprint $table) {
    //      $table->date('spid_file_upload_date')
    //         ->nullable()
    //         ->default(null);
    //     $table->foreignId('spid_forfile_upload')
    //         ->nullable()
    //         ->default(null)
    //         ->constrained('p_p_h_s');
    // });

    // Schema::table('india_post_registered_parcels', function (Blueprint $table) {
    //      $table->date('spid_file_upload_date')
    //         ->nullable()
    //         ->default(null);
    //     $table->foreignId('spid_forfile_upload')
    //         ->nullable()
    //         ->default(null)
    //         ->constrained('p_p_h_s');
    // });
    // Schema::table('india_post_speed_post_parcels', function (Blueprint $table) {
    //      $table->date('spid_file_upload_date')
    //         ->nullable()
    //         ->default(null);
    //     $table->foreignId('spid_forfile_upload')
    //         ->nullable()
    //         ->default(null)
    //         ->constrained('p_p_h_s');
    // });
    // Schema::table('india_post_business_parcels', function (Blueprint $table) {
    //      $table->date('spid_file_upload_date')
    //         ->nullable()
    //         ->default(null);
    //     $table->foreignId('spid_forfile_upload')
    //         ->nullable()
    //         ->default(null)
    //         ->constrained('p_p_h_s');
    // });


    // dd('done created');


    // Schema::table('p_p_h_bags', function (Blueprint $table) {

    //     $table->date('received_date')
    //         ->nullable()
    //         ->default(null);
    // });

    // dd('created');


    // Schema::table('c_m_s', function (Blueprint $table) {
    //     $table->string('payment_account_no')->nullable();
    //     $table->string('amount_credited')->nullable();
    //     $table->string('imps_no')->nullable();
    //     $table->date('payment_date')->nullable();
    //     $table->string('wallet_status')->nullable()->default(1);
    // });

    // Schema::table('p_p_h_s', function (Blueprint $table) {
    //     $table->string('payment_account_no')->nullable();
    //     $table->string('amount_credited')->nullable();
    //     $table->string('imps_no')->nullable();
    //     $table->date('payment_date')->nullable();
    //     $table->string('wallet_status')->nullable()->default(1);
    // });
    // Schema::table('delivery_boys', function (Blueprint $table) {
    //     $table->bigInteger('commission')->nullable();
    // });


    // dd('created');

    // Schema::table('cms_kycs', function (Blueprint $table) {
    //     $table->string('other_document')->nullable()->after('photo');
    //     $table->string('video_kyc')->nullable()->after('other_document');
    // });

    // Schema::table('p_p_h_kycs', function (Blueprint $table) {
    //     $table->string('other_document')->nullable()->after('photo');
    //     $table->string('video_kyc')->nullable()->after('other_document');
    // });


    // dd('mmmmmmmmm');


    // Schema::table('delivery_boys', function (Blueprint $table) {
    //     $table->float('commission')->nullable()->change();
    // });



    // dd("cccc");

    // Schema::table('delivery_boys', function (Blueprint $table) {
    //     $table->integer('status')->nullable()->default(0);
    // });

    // dd('mmmmmmmmm');

    public function index(Request $request)

    {


        // Schema::table('gotogo_business_parcels', function (Blueprint $table) {
        //     $table->foreignId('destination_cms_bag_id')->nullable()->default(null)->constrained('c_m_s_bags');
        // });



        // Schema::table('franchises', function (Blueprint $table) {
        //     $table->dropColumn([
        //         'gotogo_speed_post',
        //         'gotogo_business_parcel',
        //         'gotogo_post_registered',
        //         'india_post_speed',
        //         'india_post_business',
        //         'india_post_registered'
        //     ]);
        // });

        // Schema::table('c_m_s', function (Blueprint $table) {
        //     $table->integer('gotogo_speed_post')->default(0);
        //     $table->integer('gotogo_business_parcel')->default(0);
        //     $table->integer('gotogo_post_registered')->default(0);
        //     $table->integer('india_post_speed')->default(0);
        //     $table->integer('india_post_business')->default(0);
        //     $table->integer('india_post_registered')->default(0);
        //     $table->integer('e2e')->default(0);
        //     $table->integer('e2h')->default(0);
        // });
        // Schema::table('p_p_h_s', function (Blueprint $table) {
        //     $table->integer('gotogo_speed_post')->default(0);
        //     $table->integer('gotogo_business_parcel')->default(0);
        //     $table->integer('gotogo_post_registered')->default(0);
        //     $table->integer('india_post_speed')->default(0);
        //     $table->integer('india_post_business')->default(0);
        //     $table->integer('india_post_registered')->default(0);
        //     $table->integer('e2e')->default(0);
        //     $table->integer('e2h')->default(0);
        // });
        // Schema::table('delivery_boys', function (Blueprint $table) {
        //     $table->integer('gotogo_speed_post')->default(0);
        //     $table->integer('gotogo_business_parcel')->default(0);
        //     $table->integer('gotogo_post_registered')->default(0);
        //     $table->integer('india_post_speed')->default(0);
        //     $table->integer('india_post_business')->default(0);
        //     $table->integer('india_post_registered')->default(0);
        //     $table->integer('e2e')->default(0);
        //     $table->integer('e2h')->default(0);
        // });

        // dd('done');



        // Schema::table('mails', function (Blueprint $table) {

        //     $table->date('delivered_date')->nullable();
        // });


        // Schema::table('recipients', function (Blueprint $table) {

        //     $table->string('recipient_email')->nullable();
        // });

        // Schema::table('mail_attachments', function (Blueprint $table) {

        //     $table->string('pages')->nullable();
        // });

        // Schema::table('franchises', function (Blueprint $table) {

        //     $table->string('fcm_token')->nullable();
        // });
        // Schema::table('c_m_s', function (Blueprint $table) {

        //     $table->string('fcm_token')->nullable();
        // });
        // Schema::table('p_p_h_s', function (Blueprint $table) {

        //     $table->string('fcm_token')->nullable();
        // });
        // Schema::table('delivery_boys', function (Blueprint $table) {

        //     $table->string('fcm_token')->nullable();
        // });

        // Schema::table('users', function (Blueprint $table) {

        //     $table->string('fcm_token')->nullable();
        // });

        // dd('jjjjjjjjjjjjjj');

        $tableStructure = DB::select('DESCRIBE mails');
        return response()->json($tableStructure);


        $searchKey = $request->input('searchKey');
        $date = $request->input('date');
        $userGeneratedId = Auth::guard('delboy')->user()->generated_id;
        $parentFranchiseId = Auth::guard('delboy')->user()->franchise_id;

        $query = GotogoSuperFastParcel::whereHas('roleUser', function ($query) use ($userGeneratedId, $parentFranchiseId) {
            $query->where('email', $userGeneratedId);
            $query->where('franchise_id', $parentFranchiseId);
        });

        if ($searchKey) {

            $query->where(function ($q) use ($searchKey) {

                $q->where('pickup_name', 'LIKE', "%{$searchKey}%")

                    ->orWhere('pickup_mobile', 'LIKE', "%{$searchKey}%")

                    ->orWhere('pickup_email', 'LIKE', "%{$searchKey}%")

                    ->orWhere('pickup_pincode', 'LIKE', "%{$searchKey}%")

                    ->orWhere('pickup_city', 'LIKE', "%{$searchKey}%")

                    ->orWhere('pickup_state', 'LIKE', "%{$searchKey}%")

                    ->orWhere('pickup_address', 'LIKE', "%{$searchKey}%")

                    ->orWhere('consignee_name', 'LIKE', "%{$searchKey}%")

                    ->orWhere('consignee_mobile', 'LIKE', "%{$searchKey}%")

                    ->orWhere('consignee_email', 'LIKE', "%{$searchKey}%")

                    ->orWhere('consignee_pincode', 'LIKE', "%{$searchKey}%")

                    ->orWhere('consignee_city', 'LIKE', "%{$searchKey}%")

                    ->orWhere('consignee_state', 'LIKE', "%{$searchKey}%")

                    ->orWhere('consignee_address', 'LIKE', "%{$searchKey}%")

                    ->orWhere('barcode_no', 'LIKE', "%{$searchKey}%");
            });

            $datas = $query->get();

            return view('deliveryBoy.gotogoSuperFast.index', compact('datas'));
        }

        // Add date range filtering

        if ($date) {
            $date = Carbon::createFromFormat('d-m-Y', $date)->startOfDay()->toDateString();
            $query->whereDate('created_at', '=', $date);
            $datas = $query->get();

            return view('deliveryBoy.gotogoSuperFast.index', compact('datas'));
        } else {

            $query->whereDate('created_at', Carbon::today());
            $datas = $query->get();

            return view('deliveryBoy.gotogoSuperFast.index', compact('datas'));
        }
    }
}
