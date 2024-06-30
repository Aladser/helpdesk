<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConnectionsTable extends Migration
{
    protected $primaryKey = 'conn_id';

    public function up()
    {
        Schema::create('connections', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('conn_id')->unsigned()->nullable(false);
            $table->bigInteger('user_id')->unsigned();
            $table->integer('is_active')->default(0);

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('connections');
    }
}
