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
        Schema::create('india_post_b_r_rates', function (Blueprint $table) {
            $table->id();
            $table->string('distance'); 
            $table->string('upto_2kg'); 
            $table->string('addl_upto_5kg'); 
            $table->string('above_upto_5kg'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('india_post_b_r_rates');
    }
};
