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
        Schema::table('india_post_barcodes', function (Blueprint $table) {
            $table->integer('service_type')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('india_post_barcodes', function (Blueprint $table) {
           $table->dropColumn('service_type');
        });
    }
};
