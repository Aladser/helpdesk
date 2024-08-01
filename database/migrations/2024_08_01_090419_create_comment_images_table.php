<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentImagesTable extends Migration
{
    public function up()
    {
        Schema::create('comment_images', function (Blueprint $table) {
            $table->id();
            $table->string('image', 255);
            $table->bigInteger('comment_id')->unsigned();

            $table->foreign('comment_id')->references('id')->on('comments')->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('comment_images');
    }
}
