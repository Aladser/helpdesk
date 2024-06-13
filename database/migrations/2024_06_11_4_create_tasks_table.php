<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTasksTable extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('header', 100);
            $table->text('content');
            $table->bigInteger('author_id')->unsigned();
            $table->foreign('author_id')->references('id')->on('users')->cascadeOnDelete();
            $table->bigInteger('executor_id')->unsigned();
            $table->foreign('executor_id')->references('id')->on('users')->cascadeOnDelete();
            $table->bigInteger('status_id')->unsigned();
            $table->foreign('status_id')->references('id')->on('statuses')->cascadeOnDelete();
            $table->timestamps();         
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
