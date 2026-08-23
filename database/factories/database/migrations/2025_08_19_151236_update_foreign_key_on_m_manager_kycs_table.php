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
        // पहले index हटाओ (क्योंकि ये foreign key नहीं है)
        $table->dropIndex('m_manager_kycs_e_customer_id_foreign');

        // अब कॉलम को nullable बनाओ
        $table->unsignedBigInteger('m_manager_id')->nullable()->change();

        // अब नया सही foreign key बनाओ
        $table->foreign('m_manager_id')
              ->references('id')
              ->on('m_managers')   // अब m_managers से link होगा
              ->onDelete('set null')
              ->onUpdate('cascade');
    });
}

public function down(): void
{
    Schema::table('m_manager_kycs', function (Blueprint $table) {
        $table->dropForeign(['m_manager_id']);

        $table->unsignedBigInteger('m_manager_id')->nullable(false)->change();

        // वापस पुराना index add कर दो
        $table->index('m_manager_id', 'm_manager_kycs_e_customer_id_foreign');
    });
}


};
