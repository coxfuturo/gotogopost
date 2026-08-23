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
        Schema::create('e2_h_track_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mail_id')->constrained('mails')->onDelete('cascade');
            $table->string('mail_code');
            $table->foreignId('source_franchise_id')->nullable()->constrained('franchises')->onDelete('cascade');
            $table->foreignId('destination_franchise_id')->nullable()->constrained('franchises')->onDelete('cascade');
            $table->string('delivery_boy_assigned_datetime')->nullable();
            $table->dateTime('delivery_datetime')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('e2_h_track_orders');
    }
};
