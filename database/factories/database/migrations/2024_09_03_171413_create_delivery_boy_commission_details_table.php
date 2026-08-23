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
        Schema::create('delivery_boy_commission_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_boy_id')->nullable()->default(null)->constrained('delivery_boys');
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
        Schema::dropIfExists('delivery_boy_commission_details');
    }
};
