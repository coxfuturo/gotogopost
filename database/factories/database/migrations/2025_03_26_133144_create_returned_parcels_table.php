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
        Schema::create('returned_parcels', function (Blueprint $table) {
            $table->id();
            $table->string('parcel_type')->nullable();
            $table->string('parcel_id')->nullable();
            $table->string('service_type')->nullable();
            $table->unsignedBigInteger('source_franchise_bag_id')->nullable();
            $table->unsignedBigInteger('destination_franchise_bag_id')->nullable();
            $table->unsignedBigInteger('source_cms_bag_id')->nullable();
            $table->unsignedBigInteger('destination_cms_bag_id')->nullable();
            $table->unsignedBigInteger('source_pph_bag_id')->nullable();
            $table->unsignedBigInteger('delivery_boy_id')->nullable();
            $table->unsignedBigInteger('pickup_boy_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returned_parcels');
    }
};
