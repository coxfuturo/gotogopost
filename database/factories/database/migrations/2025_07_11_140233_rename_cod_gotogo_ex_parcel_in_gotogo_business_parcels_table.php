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
            $table->renameColumn('cod_gotogo_ex_parcel', 'cod_customer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gotogo_business_parcels', function (Blueprint $table) {
             $table->renameColumn('cod_gotogo_ex_parcel', 'cod_customer_id');
        });
    }
};
