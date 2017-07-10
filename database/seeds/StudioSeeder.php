<?php

use App\Entity\Anchor;
use App\Entity\Channel;
use App\Entity\StudioChannel;
use Illuminate\Database\Seeder;

class StudioSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    factory(Channel::class, 5)->create();
    factory(StudioChannel::class, 10)->create();
    factory(Anchor::class, 30)->create();
  }
}
