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
        Schema::create('franchise_barcode_series', function (Blueprint $table) {
            $table->id();
            $table->foreignId('franchise_id')
                ->constrained('franchises')
                ->onDelete('cascade');
            $table->string('last_parcel_code_issued')->nullable();
            $table->string('last_bag_code_issued')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('franchise_barcode_series');
    }
};
