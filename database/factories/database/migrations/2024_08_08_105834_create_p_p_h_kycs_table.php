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
        Schema::create('p_p_h_kycs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('adhar_card')->nullable();
            $table->string('pan_card')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('adhar_front_img')->nullable();
            $table->string('adhar_back_img')->nullable();
            $table->string('cheque_img')->nullable();
            $table->string('pan_img')->nullable();
            $table->string('photo')->nullable();
            $table->string('pph_img')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0:not applied,1:pending,2:Approved,3:reject');
            $table->string('applied_at')->nullable();
            $table->string('approved_at')->nullable();
            $table->string('rejected_at')->nullable();
            $table->text('reject_reason')->nullable();
            $table->bigInteger('pph_id')->unsigned();
            $table->foreign('pph_id')->references('id')->on('p_p_h_s')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_p_h_kycs');
    }
};
