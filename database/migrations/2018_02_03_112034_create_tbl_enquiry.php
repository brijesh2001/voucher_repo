<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblEnquiry extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_enquiry', function (Blueprint $table){
            $table->increments('id');
            $table->string('email');
            $table->string('name');
            $table->string('mobile');
            $table->tinyInteger('number_of_voucher');
            $table->decimal('rate');
            $table->string('payment_request_id');
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
        Schema::drop('tbl_enquiry');
    }
}
