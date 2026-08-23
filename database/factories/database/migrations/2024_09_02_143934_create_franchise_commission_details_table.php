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
        Schema::create('franchise_commission_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('franchise_id')
                ->constrained('franchises')
                ->onDelete('cascade');
            $table->string('service_type')->nullable();
            $table->integer('amount')->nullable();
            $table->integer('commission')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('franchise_commission_details');
    }
};
