<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PushMessage extends Job implements ShouldQueue
{
  use InteractsWithQueue, SerializesModels;
  /**
   * @var
   */
  private $ids;
  /**
   * @var
   */
  private $title;
  /**
   * @var
   */
  private $content;
  /**
   * @var array
   */
  private $data;

  /**
   * Create a new job instance.
   *
   * @param $ids
   * @param $title
   * @param $content
   * @param array $data
   */
  public function __construct($ids, $title, $content, $data = [])
  {
    $this->ids = $ids;
    $this->title = $title;
    $this->content = $content;
    $this->data = $data;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    $res = \Push::batch((array)$this->ids, $this->title, $this->content, $this->data);
    if (app()->isLocal()) {
      \Log::debug('PUSH>>>>>>>>>', [
        'data' => [
          'id'      => $this->ids,
          'title'   => $this->title,
          'content' => $this->content,
          'data'    => $this->data
        ],
        'res'  => $res
      ]);
    }
  }
}
