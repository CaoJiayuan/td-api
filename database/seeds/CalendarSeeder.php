<?php

use Illuminate\Database\Seeder;

class CalendarSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    factory(\App\Entity\Calendar::class, 20)->create();
  }
}
