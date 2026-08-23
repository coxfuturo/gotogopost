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
        Schema::table('p_p_h_s', function (Blueprint $table) {
             $table->string('natality', 100)->after('longitude');
             $table->unsignedTinyInteger('age')->nullable()->after('longitude');
             $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('longitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('p_p_h_s', function (Blueprint $table) {
            $table->dropColumn([
                'natality',
                'age',
                'gender',
            ]);
        });
    }
};
