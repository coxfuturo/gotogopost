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
        Schema::create('gotogo_bussines_prints', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('franchise_id');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('gotogo_busines_id');
            $table->string('status')->default(0); // Assuming 'status' is a string. You can change the type as needed.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gotogo_bussines_prints');
    }
};
