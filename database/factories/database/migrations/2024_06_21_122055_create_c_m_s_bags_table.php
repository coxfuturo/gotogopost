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
        Schema::create('c_m_s_bags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('franchise_id')->nullable()->default(null)->constrained('franchises');
            $table->foreignId('cms_id')->nullable()->default(null)->constrained('c_m_s');
            $table->foreignId('delivery_boy_id')->nullable()->default(null)->constrained('delivery_boys');
            $table->string('barcode_no');
            $table->string('barcode_img_src');
            $table->string('bag_id');
            $table->string('service_type');
            $table->string('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('c_m_s_bags');
    }
};
