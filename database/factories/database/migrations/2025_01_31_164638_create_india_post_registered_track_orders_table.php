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
        Schema::create('india_post_registered_track_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parcel_id')->constrained('india_post_registered_parcels')->onDelete('cascade');
            $table->string('barcode_no');
            $table->foreignId('source_franchise_id')->nullable()->constrained('franchises')->onDelete('cascade');
            $table->foreignId('source_cms_id')->nullable()->constrained('c_m_s')->onDelete('cascade');
            $table->foreignId('pph_id')->nullable()->constrained('p_p_h_s')->onDelete('cascade');
            $table->foreignId('destination_cms_id')->nullable()->constrained('c_m_s')->onDelete('cascade');
            $table->foreignId('destination_franchise_id')->nullable()->constrained('franchises')->onDelete('cascade');
            $table->foreignId('delivery_boy_id')->nullable()->constrained('delivery_boys')->onDelete('cascade');

            // Adding datetime and location for each tracking stage
            $table->dateTime('order_placed_datetime')->nullable();
            $table->dateTime('order_dispatch_datetime')->nullable();
            $table->string('source_franchise_location')->nullable();

            $table->dateTime('source_cms_receiving_datetime')->nullable();
            $table->dateTime('source_cms_dispatch_datetime')->nullable();
            $table->string('source_cms_location')->nullable();

            $table->dateTime('pph_receiving_datetime')->nullable();
            $table->dateTime('pph_dispatch_datetime')->nullable();
            $table->string('pph_location')->nullable();

            $table->dateTime('destination_cms_receiving_datetime')->nullable();
            $table->dateTime('destination_cms_dispatch_datetime')->nullable();
            $table->string('destination_cms_location')->nullable();

            $table->dateTime('destination_franchise_receiving_datetime')->nullable();
            $table->string('destination_franchise_location')->nullable();

            $table->string('delivery_boy_assigned_datetime')->nullable();
            $table->dateTime('delivery_datetime')->nullable();
            $table->string('delivery_location')->nullable();

            // Timestamps for created_at and updated_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('india_post_registered_track_orders');
    }
};
