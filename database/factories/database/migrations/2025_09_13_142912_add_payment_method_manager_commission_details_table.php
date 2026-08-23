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
         Schema::table('manager_commission_details', function (Blueprint $table) {
         $table->string('payment_method', 100)->after('commission_id');
          });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('manager_commission_details', function (Blueprint $table) {
        $table->dropColumn('payment_method');
         });
    }
};
