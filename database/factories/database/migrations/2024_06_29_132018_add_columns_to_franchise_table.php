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
        Schema::table('franchises', function (Blueprint $table) {
            $table->string('payment_account_no');
            $table->integer('amount_credited');
            $table->string('imps_no');
            $table->date('payment_date'); // Changed to 'date' type
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('franchises', function (Blueprint $table) {
            $table->dropColumn('payment_account_no');
            $table->dropColumn('amount_credited');
            $table->dropColumn('imps_no');
            $table->dropColumn('payment_date');
        });
    }
};
