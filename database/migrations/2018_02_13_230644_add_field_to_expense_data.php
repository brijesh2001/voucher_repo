<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldToExpenseData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add new fieles to users table
        Schema::table('tbl_expense_data', function (Blueprint $table) {
            $table->date('invoice_date')->after('date')->nullable();
            $table->string('invoice_number')->after('invoice_date');
            $table->string('gstn')->after('invoice_number');
            $table->string('hsn_sac')->after('gstn');
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
