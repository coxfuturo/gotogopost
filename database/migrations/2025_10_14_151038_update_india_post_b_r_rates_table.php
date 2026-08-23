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
        Schema::table('india_post_b_r_rates', function (Blueprint $table) {
            $table->renameColumn('distance', 'weight');
            $table->renameColumn('upto_2kg', 'local');
            $table->renameColumn('addl_upto_5kg', 'upto_200_km');
            $table->renameColumn('above_upto_5kg', '201_to_1000_km');
            $table->decimal('1001_to_2000_km', 8, 2)->default(0);
            $table->decimal('above_2000_km', 8, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('india_post_b_r_rates', function (Blueprint $table) {
            $table->renameColumn('distance', 'weight');
            $table->renameColumn('upto_2kg', 'local');
            $table->renameColumn('addl_upto_5kg', 'upto_200_km');
            $table->renameColumn('above_upto_5kg', '201_to_1000_km');
            $table->dropColumn(['1001_to_2000_km','above_2000_km']);
        });
    }
};
