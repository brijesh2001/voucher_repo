<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableExpenseData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_expense_data', function (Blueprint $table){
            $table->increments('id');
            $table->date('date');
            $table->string('name');
            $table->string('detail');
            $table->decimal('before_gst');
            $table->decimal('gst');
            $table->decimal('after_gst');
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
        Schema::drop('tbl_expense_data');
    }
}
