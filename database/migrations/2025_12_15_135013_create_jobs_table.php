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
        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary key
            $table->string('queue')->index();
            $table->longText('payload');
            $table->tinyInteger('attempts')->unsigned()->default(0);
            $table->integer('reserved_at')->unsigned()->nullable();
            $table->integer('available_at')->unsigned();
            $table->integer('created_at')->unsigned();
            
            // optional: Laravel recommends `index` on reserved_at
            $table->index('reserved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
