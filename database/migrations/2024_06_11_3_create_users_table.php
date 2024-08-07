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
            $table->string('login')->unique();
            $table->string('name')->nullable(true);
            $table->string('surname')->nullable(true);
            $table->string('patronym')->nullable(true);
            $table->string('password');
            $table->bigInteger('role_id')->unsigned();
            $table->bigInteger('status_id')->unsigned()->default(3);

            $table->foreign('role_id')->references('id')->on('user_roles')->cascadeOnDelete();
            $table->foreign('status_id')->references('id')->on('user_statuses')->cascadeOnDelete();
            $table->rememberToken();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
