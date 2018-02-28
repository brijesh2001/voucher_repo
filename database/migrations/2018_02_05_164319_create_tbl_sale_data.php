<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblSaleData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_sale_data', function (Blueprint $table){
            $table->increments('id');
            $table->integer('enquiry_id');
            $table->string('voucher_id');
            $table->string('payment_code');
            $table->decimal('rate');
            $table->decimal('amount_paid');
            $table->integer('number_of_voucher');
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
        Schema::drop('tbl_sale_data');
    }
}
