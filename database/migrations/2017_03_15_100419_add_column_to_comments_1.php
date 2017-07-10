<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddColumnToComments1 extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('comments', function (Blueprint $table) {
      $table->unsignedInteger('parent_id')->nullable()->default(0);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('comments', function (Blueprint $table) {
      $table->dropColumn('parent_id');
    });
  }
}
