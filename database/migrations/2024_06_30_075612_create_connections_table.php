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
            $table->bigInteger('conn_id')->unsigned();
            $table->string('login');
            $table->boolean('is_active')->default(false);
        });
    }

    public function down()
    {
        Schema::dropIfExists('connections');
    }
}
