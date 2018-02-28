<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // create permission table
        Schema::create('tbl_permission', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',100)->nullable()->comment('Permission Name!');
            $table->string('code',50)->nullable()->comment('Permission short code!');
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
        Schema::drop('tbl_permission');
    }
}
