<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOverseasPayment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_overseas_payment', function (Blueprint $table){
            $table->increments('id');
            $table->date('payment_date');
            $table->string('payment_type');
            $table->string('invoice_no');
            $table->string('name');
            $table->string('email');
            $table->string('mobile');
            $table->string('lut');
            $table->string('item');
            $table->string('detail');
            $table->string('transaction_id');
            $table->float('amount');
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
        Schema::drop('tbl_overseas_payment');
    }
}
