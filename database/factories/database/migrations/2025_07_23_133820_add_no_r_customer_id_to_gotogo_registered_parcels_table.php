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
        Schema::table('gotogo_registered_parcels', function (Blueprint $table) {
            $table->string('no_r_customer_id')->after('bag_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gotogo_registered_parcels', function (Blueprint $table) {
            $table->dropColumn('no_r_customer_id');
        });
    }
};
