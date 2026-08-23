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
        Schema::create('delivery_boys', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('mobile');
            $table->string('email')->nullable();
            $table->integer('pincode');
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('state')->nullable();
            $table->double('wallet_balance');
            $table->string('address')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('image')->nullable();
            $table->string('adhar_card')->nullable();
            $table->string('adhar_front_img')->nullable();
            $table->string('adhar_back_img')->nullable();
            $table->string('password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_boys');
    }
};
