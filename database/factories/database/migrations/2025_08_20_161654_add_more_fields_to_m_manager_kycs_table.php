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
          
    $table->string('collage', 255)->nullable()->after('m_manager_id'); 
    $table->string('marksheetType', 100)->nullable();
    $table->string('grade', 50)->nullable();
    $table->decimal('percentage', 5, 2)->nullable();
    $table->string('marksheet', 255)->nullable();
    $table->unsignedSmallInteger('experience')->nullable(); 
    $table->date('start_date')->nullable();
    $table->date('end_date')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('m_manager_kycs', function (Blueprint $table) {
             $table->dropColumn([
                'collage',
                'marksheetType',
                'grade',
                'percentage',
                'marksheet',
                'experience',
                'start_date',
                'end_date',
            ]);
        });
    }
};
