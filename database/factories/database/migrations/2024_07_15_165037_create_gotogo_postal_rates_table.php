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
        Schema::create('gotogo_postal_rates', function (Blueprint $table) {
            $table->id();
            $table->string('weight');
            $table->float('withinCity_zone_A', 8, 2);
            $table->float('upto_500_kms_zone_B', 8, 2);
            $table->float('metro_to_metro_zone_C', 8, 2);
            $table->float('rest_of_india_zone_D', 8, 2);
            $table->float('ne_jnk_zone_E', 8, 2);
            $table->string('type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gotogo_postal_rates');
    }
};
