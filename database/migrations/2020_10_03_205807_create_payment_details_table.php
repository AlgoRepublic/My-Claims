<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_details', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('m_payment_id');
            $table->integer('pf_payment_id');
            $table->string('payment_status');
            $table->string('item_name');
            $table->string('item_description');
            $table->float('amount_gross');
            $table->float('amount_fee');
            $table->float('amount_net');
            $table->integer('merchant_id');
            $table->string('token');
            $table->string('billing_date');
            $table->string('payment_method')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_details');
    }
}
