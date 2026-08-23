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
       Schema::create('e_customers', function (Blueprint $table) {
    $table->id();
    $table->string('register_type')->nullable(); 
    $table->string('customer_no')->nullable(); 
    $table->text('name'); 
    $table->string('father_name')->nullable(); 
    $table->string('mobile'); 
    $table->string('gst_number')->nullable(); 
    $table->integer('cph_link')->default(0); 
    $table->string('location')->nullable(); 
    $table->string('email')->unique(); 
    $table->integer('pincode'); 
    $table->string('city')->nullable(); 
    $table->string('district')->nullable(); 
    $table->string('state')->nullable(); 
    $table->string('code', 10)->nullable(); 
    $table->text('address')->nullable(); 
    $table->string('latitude')->nullable(); 
    $table->string('longitude')->nullable(); 
    $table->string('society')->nullable(); 
    $table->string('sector')->nullable(); 

    $table->tinyInteger('status')->default(0)->comment('1:active,0:inactive');
    $table->double('cod_charges')->default(0); 
    $table->double('delivery_charges')->default(0);
    $table->integer('payment_status')->default(0);
    $table->double('commission')->default(0);

    $table->string('password');

    $table->string('payment_account_no')->nullable();
    $table->integer('amount_credited')->default(0);
    $table->string('imps_no')->nullable();
    $table->date('payment_date')->nullable();

    $table->integer('gotogo_business_parcel')->default(0);

    $table->integer('india_post_speed')->default(0);
    $table->integer('india_post_business')->default(0);
    $table->integer('india_post_registered')->default(0);

    $table->string('verification_otp')->nullable();
    $table->string('fcm_token')->nullable(); 

    $table->double('gotogo_balance')->default(0);
    $table->double('indiapost_balance')->default(0);
    $table->double('credit_balance')->default(0);
    $table->timestamps();
});


         Schema::create('e_customer_kycs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('e_customer_id')->unsigned();
            $table->bigInteger('adhar_card')->nullable();
            $table->string('pan_card')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('account_number')->nullable();
            $table->text('adhar_front_img')->nullable();
            $table->text('adhar_back_img')->nullable();
            $table->text('cheque_img')->nullable();
            $table->text('pan_img')->nullable();
            $table->text('photo')->nullable();
            $table->text('other_document')->nullable();
            $table->text('video_kyc')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0:not applied,1:pending,2:Approved,3:reject');
            $table->string('applied_at')->nullable();
            $table->string('approved_at')->nullable();
            $table->string('rejected_at')->nullable();
            $table->text('reject_reason')->nullable();
            $table->foreign('e_customer_id')->references('id')->on('e_customers')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('e_customers');
    }
};
