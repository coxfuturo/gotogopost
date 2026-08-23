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
        Schema::create('manager_commission_details', function (Blueprint $table) {
            $table->id();
            $table->string('commission_id');
            $table->decimal('amount', 10, 2); // Use decimal instead of float for currency
            $table->decimal('commission', 10, 2); // Use decimal instead of float for currency
            $table->string('type');
            $table->unsignedBigInteger('servicetype');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manager_commission_details');
    }
};
