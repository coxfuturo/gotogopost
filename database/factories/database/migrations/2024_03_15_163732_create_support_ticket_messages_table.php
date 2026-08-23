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
        Schema::create('support_ticket_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('support_ticket_id')->unsigned();
            $table->integer('messageable_id')->unsigned();
            $table->string('messageable_type');
            $table->longText('body');
            $table->tinyInteger('is_read')->comment('0: Un-Read, 1: Read')->default(0);
            $table->timestamps();
            $table->foreign('support_ticket_id')->references('id')->on('support_tickets');
         
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_ticket_messages');
    }
};
