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
        Schema::table('c_m_s_payments', function (Blueprint $table) {
            // $table->dropForeign(['cms_id']); 
            $table->foreign('cms_id')
                  ->references('id')
                  ->on('c_m_s')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('c_m_s_payments', function (Blueprint $table) {
            // $table->dropForeign(['cms_id']); 
            $table->foreign('cms_id')
                  ->references('id')
                  ->on('c_m_s'); // No cascade here (rollback)
        });
    }
};
