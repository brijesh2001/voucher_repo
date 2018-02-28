<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldPurchasedData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add new fieles to users table
        Schema::table('tbl_purchase_data', function (Blueprint $table) {
            $table->string('rtgs')->after('invoice_number');
            $table->string('narration')->after('rtgs');
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
