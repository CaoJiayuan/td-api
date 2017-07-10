<?php

use App\Entity\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    factory(Banner::class, 5)->create();
  }
}
