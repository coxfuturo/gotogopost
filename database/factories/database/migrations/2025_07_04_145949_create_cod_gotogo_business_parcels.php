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
        Schema::create('cod_gotogo_business_parcels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('phone');
            $table->string('gst_no')->nullable();
            $table->string('email')->unique();
            $table->string('image')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->bigInteger('pincode');
            $table->string('city');
            $table->string('state');
            $table->string('address');
            $table->string('password');
            $table->tinyInteger('status')->default(0)->comment('1:Active,0:Inactive');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cod_gotogo_business_parcels');
    }
};
