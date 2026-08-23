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
        Schema::create('c_m_s', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('cms_name')->nullable();
            $table->string('mobile');
            $table->string('email')->unique();
            $table->integer('pincode')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('state')->nullable();
            $table->string('address')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->tinyInteger('status')->default(0)->comment('1:active,0:inactive');
            $table->double('commission')->default(0);
            $table->string('password');
            $table->timestamps();
        });

        Schema::create('cms_kycs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cms_id')->unsigned();
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
            $table->string('cms_img')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0:not applied,1:pending,2:Approved,3:reject');
            $table->string('applied_at')->nullable();
            $table->string('approved_at')->nullable();
            $table->string('rejected_at')->nullable();
            $table->text('reject_reason')->nullable();
            $table->foreign('cms_id')->references('id')->on('c_m_s')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('c_m_s');
        Schema::dropIfExists('cms_kycs');
    }
};
