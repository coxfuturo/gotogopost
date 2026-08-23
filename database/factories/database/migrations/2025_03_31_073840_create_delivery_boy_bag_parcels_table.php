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
        Schema::create('delivery_boy_bag_parcels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bag_id')->index();
            $table->json('parcels'); // JSON field to store multiple parcels
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_boy_bag_parcels');
    }
};
