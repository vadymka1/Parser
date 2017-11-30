<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration
{
    public function up()
    {
        Schema::create('news', function (Blueprint $table){
            $table->increments('id');
            $table->integer('news_id');
            $table->string('link');
            $table->string('title');
            $table->timestamp('date');
            $table->string('tags');
            $table->integer('views');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('news');
    }
}