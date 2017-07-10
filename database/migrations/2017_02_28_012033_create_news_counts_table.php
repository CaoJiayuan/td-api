<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsCountsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('news_counts', function (Blueprint $table) {
      $table->increments('id');
      $table->decimal('news_id', 10, 0)->index()->comment('新闻id');
      $table->unsignedInteger('read')->comment('阅读量');
      $table->unsignedInteger('like')->comment('点赞量');
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
    Schema::dropIfExists('news_counts');
  }
}
