<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusesTable extends Migration
{
    public function up()
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->unique();
            $table->string('description', 255)->unique();
        });
    }

    public function down()
    {
        Schema::dropIfExists('statuses');
    }
}

