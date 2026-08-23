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
            $table->string('franchise_no')->after('generated_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('franchises', function (Blueprint $table) {
            $table->dropColumn('franchise_no');
        });
    }
};
