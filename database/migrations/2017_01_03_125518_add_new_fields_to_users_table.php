<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       // Add new fieles to users table
	   Schema::table('users', function (Blueprint $table) {
       		$table->string('first_name')->after('name')->nullable()->comment('User first name!');
			$table->string('last_name')->after('first_name')->nullable()->comment('User last name!');
			$table->integer('role_id')->default('0')->after('email');
            $table->integer('status',false, true)->length(1)->after('password')->default('0')->comment('0=Inactive,1=Active');
            $table->integer('created_by')->default('0')->after('status');
            $table->integer('updated_by')->default('0')->after('created_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::drop('users');
    }
}
