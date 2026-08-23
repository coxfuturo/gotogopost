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
        Schema::table('delivery_boys', function (Blueprint $table) {
            $table->foreignId('franchise_id')->nullable()->default(null)->constrained('franchises')->after('id');
            $table->foreignId('cms_id')->nullable()->default(null)->constrained('c_m_s')->after('franchise_id');
            $table->string('generated_id')->after('cms_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delivery_boys', function (Blueprint $table) {
            $table->dropForeign(['franchise_id']);
            $table->dropForeign(['cms_id']);
            $table->dropColumn('generated_id');
        });
    }
};
