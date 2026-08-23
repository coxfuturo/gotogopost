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
        Schema::create('franchise_barcodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('franchise_barcodeseries_id')
                ->constrained('franchise_barcode_series')
                ->onDelete('cascade');
            $table->string('barcodes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('franchise_barcodes');
    }
};
