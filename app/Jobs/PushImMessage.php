<?php

namespace App\Jobs;

use App\Entity\ChatterService;
use App\Entity\ImMessage;
use App\Entity\ImReceiver;
use App\Entity\ImUser;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Im;

class PushImMessage extends Job implements ShouldQueue
{
  use InteractsWithQueue, SerializesModels;
  /**
   * @var
   */
  private $from;
  /**
   * @var
   */
  private $to;
  /**
   * @var
   */
  private $summary;
  /**
   * @var array
   */
  private $data;
  /**
   * @var int
   */
  private $type;

  /**
   * Create a new job instance.
   *
   * @param ImUser $from
   * @param $to
   * @param $summary
   * @param array $data
   * @param int $type
   */
  public function __construct($from, $to, $summary, $type = null, $data = [])
  {
    if ($type === null) {
      $type = Im::getTypeText();
    }
    $this->from = $from;
    $this->to = $to;
    $this->summary = $summary;
    $this->data = $data;
    $this->type = $type;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    $response = Im::sendMessage($this->from->im_id, $this->to, $this->summary, $this->type, $this->data);
    $msg = Im::getMessage($this->from->im_id, $this->to, $this->type, $this->summary, $this->data);

    $isHello = array_get($msg, 'message._lcattrs.hello', false);
    if(!$isHello) {
      \DB::transaction(function () use ($msg) {
        $to = (array)$this->to;
        $msg['conv_id'] = $to;
        $message = ImMessage::create([
          'sender_id' => $this->from->id,
          'type'      => $this->type,
          'summary'   => $this->summary,
          'msg'       => json_encode(array_get($msg, 'message', [])),
        ]);
        $msg = json_encode($msg);
        $res = ImUser::whereIn('con_id', $to)->get();
//        \Log::debug('>>cons', $to);
        $receivers = [];
        foreach ($res as $re) {
          $receivers[] = [
            'im_message_id' => $message->id,
            'receiver_id'   => $re->id,
          ];
        }
        if ($receivers) {
          ImReceiver::insert($receivers);
        }

        ChatterService::rightJoin('im_users', function (JoinClause $clause) {
          $clause->on('im_users.id', '=', 'chatter_services.chatter_id');
          $clause->on('im_users.service_id', '=', 'chatter_services.service_id');
        })->where('im_users.con_id', $this->to)->update([
          'last_msg_at' => Carbon::now(),
          'last_msg'    => $msg
        ]);
      });
    }

    $isHello || \Log::alert('>>>>>>', [
      'data'     => $this->data,
      'response' => $response
    ]);
  }
}
