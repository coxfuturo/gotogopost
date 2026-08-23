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
        Schema::create('p_p_h_s', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('pph_no');
            $table->string('generated_id');
            $table->string('father_name');
            $table->string('mobile');
            $table->string('email')->unique();
            $table->integer('pincode')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('state')->nullable();
            $table->string('address')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->tinyInteger('status')->default(0)->comment('1:active,0:inactive');
            $table->double('commission')->default(0);
            $table->double('wallet_balance')->default(0);
            $table->double('remaining_balance')->default(0);
            $table->string('password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_p_h_s');
    }
};
