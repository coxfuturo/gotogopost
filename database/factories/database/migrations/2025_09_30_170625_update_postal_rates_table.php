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
        Schema::table('postal_rates', function (Blueprint $table) {
            $table->renameColumn('201_to_1000_kms', 'rate_201_to_500_kms');
            $table->renameColumn('1001_to_2000_kms', 'rate_501_to_1000_kms');
             $table->decimal('rate_1001_to_2000_kms', 8, 2)->nullable()->after('1001_to_2000_kms');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('postal_rates', function (Blueprint $table) {
            $table->renameColumn('rate_201_to_1000_kms', 'rate_201_to_500_kms');
            $table->renameColumn('rate_1001_to_2000_kms', 'rate_501_to_1000_kms');
            $table->dropColumn('rate_1001_to_2000_kms');
        });
    }
};
