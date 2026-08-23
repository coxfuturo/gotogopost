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
        Schema::table('gotogo_business_parcels', function (Blueprint $table) {
            $table->integer('cod_gotogo_ex_parcel')->after('bag_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gotogo_business_parcels', function (Blueprint $table) {
            $table->dropColumn('cod_gotogo_ex_parcel');
        });
    }
};
