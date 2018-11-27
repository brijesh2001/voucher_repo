<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColomnToOverseasPayment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_overseas_payment', function (Blueprint $table) {
            $table->string('address');
            $table->string('country');
            $table->float('dollor_amount_received');
            $table->float('dollor_amount_paid');

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
