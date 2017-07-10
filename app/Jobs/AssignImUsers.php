<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AssignImUsers extends Job implements ShouldQueue
{
  use InteractsWithQueue, SerializesModels;

  public function handle()
  {
    (new \App\Console\Commands\AssignImUsers())->handle();
  }
}
