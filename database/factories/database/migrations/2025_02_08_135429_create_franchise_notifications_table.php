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
        Schema::create('franchise_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('franchise_id')->nullable()->constrained('franchises')->onDelete('cascade');
            $table->string('service_type');
            $table->text('message');
            $table->string('barcode_no');
            $table->string('pickup_location');
            $table->string('current_location')->nullable();
            $table->string('parcel_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('franchise_notifications');
    }
};
