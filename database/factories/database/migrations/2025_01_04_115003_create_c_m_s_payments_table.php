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
        Schema::create('c_m_s_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cms_id')->nullable()->default(null)->constrained('c_m_s');
            $table->string('razorpay_payment_id'); 
            $table->decimal('amount', 10, 2); 
            $table->string('status')->default('pending'); 
            $table->string('method')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms_payments');
    }
};
