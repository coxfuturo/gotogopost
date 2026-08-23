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
        Schema::create('gotogo_registered_parcels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('franchise_id')->nullable()->default(null)->constrained('franchises');
            $table->foreignId('cms_id')->nullable()->default(null)->constrained('c_m_s');
            $table->foreignId('bag_id')->nullable()->default(null)->constrained('bags');
            $table->string('pickup_name');
            $table->string('pickup_mobile');
            $table->string('pickup_email');
            $table->string('pickup_pincode');
            $table->string('pickup_city');
            $table->string('pickup_state');
            $table->text('pickup_address');
            $table->string('consignee_name');
            $table->string('consignee_mobile');
            $table->string('consignee_email');
            $table->string('consignee_pincode');
            $table->string('consignee_city');
            $table->string('consignee_state');
            $table->text('consignee_address');
            $table->decimal('package_weight', 8, 2);
            $table->decimal('package_length', 8, 2);
            $table->decimal('package_width', 8, 2);
            $table->decimal('package_height', 8, 2);
            $table->string('payment_method');
            $table->decimal('payment_amount', 8, 2);
            $table->string('barcode_no');
            $table->text('barcode_image_src')->nullable();
            $table->string('insert_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gotogo_registered_parcels');
    }
};
