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
            $table->date('cancel')->nullable()->after('status');
             $table->date('cancel_date')->nullable()->after('cancel');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('india_post_business_parcels', function (Blueprint $table) {
            $table->dropColumn(['cancel_date','cancel']);
        });
    }
};
