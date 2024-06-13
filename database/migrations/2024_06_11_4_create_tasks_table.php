<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('header', 100);
            $table->text('content');
            $table->bigInteger('author_id')->unsigned();
            $table->bigInteger('executor_id')->unsigned();
            $table->bigInteger('status_id')->unsigned();
            $table->timestamps();
            $table->foreign('author_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('executor_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('status_id')->references('id')->on('statuses')->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
