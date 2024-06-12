<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('password');
            $table->bigInteger('role_id')->unsigned();
            $table->foreign('role_id')->references('id')->on('user_roles')->cascadeOnDelete();
            $table->rememberToken(); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
