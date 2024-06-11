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
            $table->enum('status', ['new', 'process', 'completed']);
            $table->bigInteger('author_id')->unsigned();
            $table->foreign('author_id')->references('id')->on('users')->cascadeOnDelete();
            $table->bigInteger('executor_id')->unsigned();
            $table->foreign('executor_id')->references('id')->on('users')->cascadeOnDelete();
            $table->dateTime('last_activity');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
