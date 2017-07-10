<?php

namespace App\Console\Commands;

use App\Entity\ChatterService;
use App\Entity\ImUser;
use App\Entity\User;
use App\Repositories\ServiceRepository;
use Illuminate\Console\Command;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Im;

class AssignImUsers extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'im:assign';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Assign im users to broker';


  public function handle()
  {
    $sr = new ServiceRepository();
    $builder = User::leftJoin('im_users', function (JoinClause $clause) {
      $clause->on('im_users.user_id', '=', 'user.id');
      $clause->on('im_users.type', '=', \DB::raw(0));
    })->leftJoin('brokers', 'brokers.id', '=', 'user.broker_id')
      ->leftJoin('im_users as s', function (JoinClause $clause) {
        $clause->on('s.user_id', '=', 'brokers.admin_id');
        $clause->on('s.type', '=', \DB::raw(1));
      })
      ->leftJoin('im_users as is', function (JoinClause $clause) {
        $clause->on('is.id', '=', 'im_users.service_id');
        $clause->on('is.type', '=', \DB::raw(1));
      })
      ->where(function ($builder) {
        /** @var Builder $builder */
        $pre = $builder->getConnection()->getTablePrefix();
        $builder->whereNull('im_users.con_id')
          ->orWhere('is.user_id', '!=', \DB::raw("{$pre}brokers.admin_id"));
      })->whereNotNull('user.broker_id')->whereNotNull('brokers.admin_id')->whereNotNull('s.id');
    $users = $builder->get(['user.*', 'brokers.admin_id as admin_id', 'is.user_id as admin_id2','is.con_id', 's.id as bs_id', 's.im_id as bs_im_id']);
    $uIds = [];
    $cIds = [];
    foreach ($users as $user) {
      $uIds[] = $user->id;
      $user->con_id && $cIds[] = $user->con_id;
    }
    if($cIds) {
      Im::disconnect($cIds);
    }
    foreach ($users as $user) {
      \DB::transaction(function () use($sr, $user) {
        $imId = $sr->generateImId();
        $con = Im::createConversion([
          $user->bs_im_id,
          $imId,
        ]);
        $conId = array_get($con, 'objectId');
        $iu = [
          'im_id'       => $imId,
          'user_id'     => $user->id,
          'service_id'  => $user->bs_id,
          'type'        => 0,
          'im_password' => str_random(8),
          'nick'        => $user->nick_name,
          'icon_url'    => $user->avatar,
          'con_id'      => $conId
        ];
        $chatter = ImUser::updateOrCreate([
          'user_id'     => $user->id,
          'type'        => 0,
        ],$iu);
        ChatterService::create([
          'chatter_id' => $chatter->id,
          'service_id' => $user->bs_id,
        ]);
        $ca = $chatter->toArray();
        \Log::debug('Auto create chatter>>>>', $ca);
        $this->output && $this->info("Auto create im user:user_id:{$user->id},nick:{$user->nick_name},im_id:{$imId},con_id:{$conId},service_id:{$user->bs_id}");
      });
    }
    $this->output && $this->info('Process done!');
  }
}
