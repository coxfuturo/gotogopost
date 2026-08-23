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
        Schema::table('india_post_business_parcels', function (Blueprint $table) {
            $table->string('totalOtherAmount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('india_post_business_parcels', function (Blueprint $table) {
            $table->dropColumn('totalOtherAmount');
        });
    }
};
