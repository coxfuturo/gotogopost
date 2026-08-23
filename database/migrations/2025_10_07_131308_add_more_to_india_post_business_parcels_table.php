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
        Schema::table('india_post_business_parcels', function (Blueprint $table) {
    $table->string('cod_customer_id')->nullable()->after('bag_id');
    $table->string('cod_amount')->nullable()->default('0')->after('payment_amount');
    $table->string('register_amount')->nullable()->default('0')->after('cod_amount');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('india_post_business_parcels', function (Blueprint $table) {
            $table->dropColumn(['cod_customer_id','cod_amount','register_amount']);
        });
    }
};
