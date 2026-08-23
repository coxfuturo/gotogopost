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
        Schema::create('registration_payments', function (Blueprint $table) {
            $table->id();
            $table->decimal('franchise', 10, 2);
            $table->decimal('cph', 10, 2);
            $table->decimal('pph', 10, 2);
            $table->decimal('combo', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_payments');
    }
};
