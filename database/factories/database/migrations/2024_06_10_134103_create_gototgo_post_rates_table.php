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
        Schema::create('gototgo_post_rates', function (Blueprint $table) {
            $table->id();
            $table->string('weight');
            $table->float('Local', 8, 2);
            $table->float('upto_200_kms', 8, 2);
            $table->float('201_to_1000_kms', 8, 2);
            $table->float('1001_to_2000_kms', 8, 2);
            $table->float('above_2000_kms', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gototgo_post_rates');
    }
};
