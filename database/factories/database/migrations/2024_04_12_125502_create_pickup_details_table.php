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
        Schema::create('pickup_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('franchise_id')->nullable()->default(null)->constrained('franchises');
            $table->foreignId('cms_id')->nullable()->default(null)->constrained('c_m_s');            
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('pincode');
            $table->string('city');
            $table->string('state');
            $table->text('address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pickup_details');
    }
};
