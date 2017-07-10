<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNewsLikesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('news_likes', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('user_id');
      $table->decimal('news_id', 10, 0)->index()->comment('新闻id');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('news_likes');
  }
}
