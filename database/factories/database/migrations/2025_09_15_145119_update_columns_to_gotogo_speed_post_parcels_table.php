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
        Schema::table('gotogo_speed_post_parcels', function (Blueprint $table) {
            $table->string('pickup_email')->nullable()->change();
            $table->string('consignee_email')->nullable()->change();
            $table->string('consignee_mobile')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gotogo_speed_post_parcels', function (Blueprint $table) {
           $table->dropColumn(['pickup_email', 'consignee_email', 'consignee_mobile']);
        });
    }
};
