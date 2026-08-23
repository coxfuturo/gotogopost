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
        Schema::create('soft_copy_parcels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('franchise_id')->nullable()->default(null)->constrained('franchises');
            $table->foreignId('cms_id')->nullable()->default(null)->constrained('c_m_s');
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
            $table->decimal('package_weight');
            $table->decimal('package_length');
            $table->decimal('package_width');
            $table->decimal('package_height');
            $table->string('payment_method');
            $table->string('order_no');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soft_copy_parcels');
    }
};
