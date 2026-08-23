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
             $table->string('natality', 100)->after('longitude');
             $table->unsignedTinyInteger('age')->nullable()->after('natality');; 
             $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('age');;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delivery_boys', function (Blueprint $table) {
            $table->dropColumn([
                'natality',
                'age',
                'gender',
            ]);
        });
    }
};
