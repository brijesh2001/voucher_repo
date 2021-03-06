<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblOfflinePayment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_offline_payment', function (Blueprint $table){
            $table->increments('id');
            $table->date('payment_date');
            $table->string('invoice_no');
            $table->string('name');
            $table->string('email');
            $table->string('mobile');
            $table->string('gstn');
            $table->string('hsn');
            $table->string('voucher_code');
            $table->integer('number_of_voucher');
            $table->string('transaction_id');
            $table->float('rate_before_gst');
            $table->float('rate_after_gst');
            $table->float('cgst');
            $table->float('sgst');
            $table->float('igst');
            $table->integer('state');
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
        Schema::drop('tbl_offline_payment');
    }
}
