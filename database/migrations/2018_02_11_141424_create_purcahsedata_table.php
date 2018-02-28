<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurcahsedataTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_purchase_data', function (Blueprint $table){
            $table->increments('id');
            $table->date('purchase_date');
            $table->date('received_date');
            $table->string('invoice_number');
            $table->integer('quantity');
            $table->decimal('per_voucher_prize');
            $table->decimal('total_amount');
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
        Schema::drop('tbl_purchase_data');
    }
}
