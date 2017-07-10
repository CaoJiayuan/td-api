<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddColumnsToNews extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('CMD_NEWS_MAIN', function (Blueprint $table) {
      $table->unsignedInteger('read_count')->nullable()->default(0)->comment('阅读数量');
      $table->unsignedInteger('like_count')->nullable()->default(0)->comment('点赞数量');
      $table->unsignedInteger('comment_count')->nullable()->default(0)->comment('评论数量');
      $table->index([
        'read_count',
        'like_count',
        'comment_count'
      ]);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('CMD_NEWS_MAIN', function (Blueprint $table) {
      //
      $table->dropColumn([
        'read_count',
        'like_count',
        'comment_count'
      ]);
    });
  }
}
