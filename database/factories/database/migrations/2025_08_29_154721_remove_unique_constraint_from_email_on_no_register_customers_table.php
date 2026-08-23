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
        Schema::table('no_register_customers', function (Blueprint $table) {
            $table->dropUnique('no_register_customers_email_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('email_on_no_register_customers', function (Blueprint $table) {
           $table->unique('email');
        });
    }
};
