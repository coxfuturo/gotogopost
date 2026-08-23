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
        Schema::table('franchise_kycs', function (Blueprint $table) {
            $table->string('other_document')->nullable()->after('photo');
            $table->string('video_kyc')->nullable()->after('other_document');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('franchise_kycs', function (Blueprint $table) {
            $table->dropColumn('other_document');
            $table->dropColumn('video_kyc');
        });
    }
};
