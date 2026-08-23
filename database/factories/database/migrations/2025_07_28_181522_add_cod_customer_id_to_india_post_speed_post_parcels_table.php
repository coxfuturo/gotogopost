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
        Schema::table('india_post_speed_post_parcels', function (Blueprint $table) {
            $table->string('cod_customer_id')->after('bag_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('india_post_speed_post_parcels', function (Blueprint $table) {
            $table->dropColumn('cod_customer_id');
        });
    }
};
