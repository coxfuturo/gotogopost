<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('franchise_barcode_series', function (Blueprint $table) {

            // Adding columns for parcel services
            $table->string('parcel_barcode_range_start_gotoSpeed')->nullable()->after('franchise_id');
            $table->string('parcel_barcode_range_end_gotoSpeed')->nullable()->after('parcel_barcode_range_start_gotoSpeed');
            $table->string('last_parcel_code_issued_gotoSpeed')->nullable()->after('parcel_barcode_range_end_gotoSpeed');

            $table->string('parcel_barcode_range_start_gotoSuperFast')->nullable()->after('last_parcel_code_issued_gotoSpeed');
            $table->string('parcel_barcode_range_end_gotoSuperFast')->nullable()->after('parcel_barcode_range_start_gotoSuperFast');
            $table->string('last_parcel_code_issued_gotoSuperFast')->nullable()->after('parcel_barcode_range_end_gotoSuperFast');

            $table->string('parcel_barcode_range_start_gotoBusiness')->nullable()->after('last_parcel_code_issued_gotoSuperFast');
            $table->string('parcel_barcode_range_end_gotoBusiness')->nullable()->after('parcel_barcode_range_start_gotoBusiness');
            $table->string('last_parcel_code_issued_gotoBusiness')->nullable()->after('parcel_barcode_range_end_gotoBusiness');

            $table->string('parcel_barcode_range_start_gotoRegistered')->nullable()->after('last_parcel_code_issued_gotoBusiness');
            $table->string('parcel_barcode_range_end_gotoRegistered')->nullable()->after('parcel_barcode_range_start_gotoRegistered');
            $table->string('last_parcel_code_issued_gotoRegistered')->nullable()->after('parcel_barcode_range_end_gotoRegistered');

            $table->string('parcel_barcode_range_start_IPSpeed')->nullable()->after('last_parcel_code_issued_gotoRegistered');
            $table->string('parcel_barcode_range_end_IPSpeed')->nullable()->after('parcel_barcode_range_start_IPSpeed');
            $table->string('last_parcel_code_issued_IPSpeed')->nullable()->after('parcel_barcode_range_end_IPSpeed');

            $table->string('parcel_barcode_range_start_IPBusiness')->nullable()->after('last_parcel_code_issued_IPSpeed');
            $table->string('parcel_barcode_range_end_IPBusiness')->nullable()->after('parcel_barcode_range_start_IPBusiness');
            $table->string('last_parcel_code_issued_IPBusiness')->nullable()->after('parcel_barcode_range_end_IPBusiness');

            $table->string('parcel_barcode_range_start_IPRegistered')->nullable()->after('last_parcel_code_issued_IPBusiness');
            $table->string('parcel_barcode_range_end_IPRegistered')->nullable()->after('parcel_barcode_range_start_IPRegistered');
            $table->string('last_parcel_code_issued_IPRegistered')->nullable()->after('parcel_barcode_range_end_IPRegistered');

            // Adding columns for bag services
            $table->string('bag_barcode_range_start_gotoSpeed')->nullable()->after('last_parcel_code_issued_IPRegistered');
            $table->string('bag_barcode_range_end_gotoSpeed')->nullable()->after('bag_barcode_range_start_gotoSpeed');
            $table->string('last_bag_code_issued_gotoSpeed')->nullable()->after('bag_barcode_range_end_gotoSpeed');

            $table->string('bag_barcode_range_start_gotoSuperFast')->nullable()->after('last_bag_code_issued_gotoSpeed');
            $table->string('bag_barcode_range_end_gotoSuperFast')->nullable()->after('bag_barcode_range_start_gotoSuperFast');
            $table->string('last_bag_code_issued_gotoSuperFast')->nullable()->after('bag_barcode_range_end_gotoSuperFast');

            $table->string('bag_barcode_range_start_gotoBusiness')->nullable()->after('last_bag_code_issued_gotoSuperFast');
            $table->string('bag_barcode_range_end_gotoBusiness')->nullable()->after('bag_barcode_range_start_gotoBusiness');
            $table->string('last_bag_code_issued_gotoBusiness')->nullable()->after('bag_barcode_range_end_gotoBusiness');

            $table->string('bag_barcode_range_start_gotoRegistered')->nullable()->after('last_bag_code_issued_gotoBusiness');
            $table->string('bag_barcode_range_end_gotoRegistered')->nullable()->after('bag_barcode_range_start_gotoRegistered');
            $table->string('last_bag_code_issued_gotoRegistered')->nullable()->after('bag_barcode_range_end_gotoRegistered');

            $table->string('bag_barcode_range_start_IPSpeed')->nullable()->after('last_bag_code_issued_gotoRegistered');
            $table->string('bag_barcode_range_end_IPSpeed')->nullable()->after('bag_barcode_range_start_IPSpeed');
            $table->string('last_bag_code_issued_IPSpeed')->nullable()->after('bag_barcode_range_end_IPSpeed');

            $table->string('bag_barcode_range_start_IPBusiness')->nullable()->after('last_bag_code_issued_IPSpeed');
            $table->string('bag_barcode_range_end_IPBusiness')->nullable()->after('bag_barcode_range_start_IPBusiness');
            $table->string('last_bag_code_issued_IPBusiness')->nullable()->after('bag_barcode_range_end_IPBusiness');

            $table->string('bag_barcode_range_start_IPRegistered')->nullable()->after('last_bag_code_issued_IPBusiness');
            $table->string('bag_barcode_range_end_IPRegistered')->nullable()->after('bag_barcode_range_start_IPRegistered');
            $table->string('last_bag_code_issued_IPRegistered')->nullable()->after('bag_barcode_range_end_IPRegistered');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('franchise_barcode_series', function (Blueprint $table) {
            // Dropping columns for parcel services
            $table->dropColumn('parcel_barcode_range_start_gotoSpeed');
            $table->dropColumn('parcel_barcode_range_end_gotoSpeed');
            $table->dropColumn('last_parcel_code_issued_gotoSpeed');

            $table->dropColumn('parcel_barcode_range_start_gotoSuperFast');
            $table->dropColumn('parcel_barcode_range_end_gotoSuperFast');
            $table->dropColumn('last_parcel_code_issued_gotoSuperFast');

            $table->dropColumn('parcel_barcode_range_start_gotoBusiness');
            $table->dropColumn('parcel_barcode_range_end_gotoBusiness');
            $table->dropColumn('last_parcel_code_issued_gotoBusiness');

            $table->dropColumn('parcel_barcode_range_start_gotoRegistered');
            $table->dropColumn('parcel_barcode_range_end_gotoRegistered');
            $table->dropColumn('last_parcel_code_issued_gotoRegistered');

            $table->dropColumn('parcel_barcode_range_start_IPSpeed');
            $table->dropColumn('parcel_barcode_range_end_IPSpeed');
            $table->dropColumn('last_parcel_code_issued_IPSpeed');

            $table->dropColumn('parcel_barcode_range_start_IPBusiness');
            $table->dropColumn('parcel_barcode_range_end_IPBusiness');
            $table->dropColumn('last_parcel_code_issued_IPBusiness');

            $table->dropColumn('parcel_barcode_range_start_IPRegistered');
            $table->dropColumn('parcel_barcode_range_end_IPRegistered');
            $table->dropColumn('last_parcel_code_issued_IPRegistered');

            // Dropping columns for bag services
            $table->dropColumn('bag_barcode_range_start_gotoSpeed');
            $table->dropColumn('bag_barcode_range_end_gotoSpeed');
            $table->dropColumn('last_bag_code_issued_gotoSpeed');

            $table->dropColumn('bag_barcode_range_start_gotoSuperFast');
            $table->dropColumn('bag_barcode_range_end_gotoSuperFast');
            $table->dropColumn('last_bag_code_issued_gotoSuperFast');

            $table->dropColumn('bag_barcode_range_start_gotoBusiness');
            $table->dropColumn('bag_barcode_range_end_gotoBusiness');
            $table->dropColumn('last_bag_code_issued_gotoBusiness');

            $table->dropColumn('bag_barcode_range_start_gotoRegistered');
            $table->dropColumn('bag_barcode_range_end_gotoRegistered');
            $table->dropColumn('last_bag_code_issued_gotoRegistered');

            $table->dropColumn('bag_barcode_range_start_IPSpeed');
            $table->dropColumn('bag_barcode_range_end_IPSpeed');
            $table->dropColumn('last_bag_code_issued_IPSpeed');

            $table->dropColumn('bag_barcode_range_start_IPBusiness');
            $table->dropColumn('bag_barcode_range_end_IPBusiness');
            $table->dropColumn('last_bag_code_issued_IPBusiness');

            $table->dropColumn('bag_barcode_range_start_IPRegistered');
            $table->dropColumn('bag_barcode_range_end_IPRegistered');
            $table->dropColumn('last_bag_code_issued_IPRegistered');
        });
    }
};
