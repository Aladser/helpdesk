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
            $table->bigInteger('conn_id')->unsigned()->unique();
            $table->string('login');
            $table->integer('is_active')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('connections');
    }
}
