<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('comments', function (Blueprint $table) {
      $table->increments('id');
      $table->decimal('news_id', 10, 0)->index()->comment('新闻id');
      $table->text('content')->comment('内容');
      $table->unsignedInteger('user_id')->comment('用户id');

      $table->unsignedTinyInteger('status')->default(1)->comment('状态(0-已删除,1-可用)');
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
    Schema::dropIfExists('comments');
  }
}
