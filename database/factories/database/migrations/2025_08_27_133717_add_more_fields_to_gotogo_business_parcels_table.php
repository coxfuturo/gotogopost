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
            $table->text('discription')->nullable();
            $table->integer('status')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gotogo_business_parcels', function (Blueprint $table) {
           $table->dropColumn([
                'discription',
                'status',
            ]);
        });
    }
};
