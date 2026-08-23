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
        Schema::create('c_m_s_barcode_series', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cms_id')
                ->constrained('c_m_s')
                ->onDelete('cascade');
            $table->string('bag_barcode_range_start_gotoSpeed')->nullable();
            $table->string('bag_barcode_range_end_gotoSpeed')->nullable();
            $table->string('last_bag_code_issued_gotoSpeed')->nullable();

            $table->string('bag_barcode_range_start_gotoSuperFast')->nullable();
            $table->string('bag_barcode_range_end_gotoSuperFast')->nullable();
            $table->string('last_bag_code_issued_gotoSuperFast')->nullable();

            $table->string('bag_barcode_range_start_gotoBusiness')->nullable();
            $table->string('bag_barcode_range_end_gotoBusiness')->nullable();
            $table->string('last_bag_code_issued_gotoBusiness')->nullable();

            $table->string('bag_barcode_range_start_gotoRegistered')->nullable();
            $table->string('bag_barcode_range_end_gotoRegistered')->nullable();
            $table->string('last_bag_code_issued_gotoRegistered')->nullable();

            $table->string('bag_barcode_range_start_IPSpeed')->nullable();
            $table->string('bag_barcode_range_end_IPSpeed')->nullable();
            $table->string('last_bag_code_issued_IPSpeed')->nullable();

            $table->string('bag_barcode_range_start_IPBusiness')->nullable();
            $table->string('bag_barcode_range_end_IPBusiness')->nullable();
            $table->string('last_bag_code_issued_IPBusiness')->nullable();

            $table->string('bag_barcode_range_start_IPRegistered')->nullable();
            $table->string('bag_barcode_range_end_IPRegistered')->nullable();
            $table->string('last_bag_code_issued_IPRegistered')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('c_m_s_barcode_series');
    }
};
