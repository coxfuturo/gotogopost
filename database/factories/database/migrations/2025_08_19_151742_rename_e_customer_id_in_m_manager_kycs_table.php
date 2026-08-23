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
        Schema::table('m_manager_kycs', function (Blueprint $table) {
            $table->dropForeign('m_manager_kycs_e_customer_id_foreign');
            $table->renameColumn('e_customer_id', 'm_manager_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('m_manager_kycs', function (Blueprint $table) {
             $table->dropForeign(['m_manager_id']);
            $table->renameColumn('m_manager_id', 'e_customer_id');
        });
    }
};
