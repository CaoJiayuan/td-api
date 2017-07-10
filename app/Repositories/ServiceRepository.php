<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-3-22
 * Time: 下午5:27
 */

namespace App\Repositories;


use Api\StarterKit\Utils\ApiResponse;
use App\Entity\ChatterService;
use App\Entity\ImMessage;
use App\Entity\ImReceiver;
use App\Entity\ImUser;
use App\Entity\Service;
use App\Entity\User;
use App\Jobs\PushImMessage;
use App\Traits\Authenticated;
use App\Traits\ChatterAuthenticated;
use App\Traits\ModelHelper;
use App\Traits\PageAble;
use App\Traits\ServiceAuthenticated;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Im;

class ServiceRepository
{
  use Authenticated, ServiceAuthenticated, ApiResponse, PageAble, ModelHelper, ChatterAuthenticated;

  public function loginChatter($data = [])
  {
    $userId = $this->getUserId(false);
    $data = array_only($data, ['nick', 'icon_url']);
    $chatter = $this->findOrCreateUser($userId, false, $data);
    $this->matchService($chatter);
    $chatter->service;
    return $chatter;
  }

  /**
   * @param ImUser $chatter
   * @param bool $throw
   * @return ImUser|\Illuminate\Database\Eloquent\Model|null
   */
  public function matchService($chatter, $throw = false)
  {
    if ($chatter->type == 1 && $throw) {
      return $this->respondNotFound('未找到空闲的客服');
    }
    /**
     * 查找broker,如果有,就分配给broker
     */
    if ($chatter->type == 0) {
      $userId = $chatter->user_id;
      $broker = User::rightJoin('brokers', 'brokers.id', '=', 'user.broker_id')
        ->where('user.id', $userId)->select(['brokers.admin_id'])->first();
      if ($broker && ($adminId = $broker->admin_id)) {
        $serv = $this->findOrCreateUser($adminId, true);
        if ($serv->id != $chatter->service_id) {
          return $this->createConversion($serv, $chatter);
        }
      }
    }

    if (!$chatter->service_id && $chatter->type == 0) {
      $service = $this->findService();
      if ($service) {
        $this->createConversion($service, $chatter);
        return $service;
      }
      if ($throw) {
        return $this->respondNotFound('未找到空闲的客服');
      } else {
        return null;
      }
    }

    return $chatter->service;
  }

  /**
   * Assign the user to service
   * @param ImUser $service
   * @param ImUser $chatter
   * @return ImUser
   */
  public function createConversion($service, $chatter)
  {
    return \DB::transaction(function () use ($service, $chatter) {
      $con = Im::createConversion([
        $service->im_id,
        $chatter->im_id,
      ]);
      ChatterService::create([
        'chatter_id' => $chatter->id,
        'service_id' => $service->id,
      ]);
      $chatter->service_id = $service->id;
      $chatter->con_id = array_get($con, 'objectId');
      $chatter->save();
      $this->chatterSayHello($chatter, $service);
      $this->serviceSayHello($service, $chatter);
      return $service;
    });
  }

  /**
   * @return ImUser|\Illuminate\Database\Eloquent\Model
   */
  public function findService()
  {
    $builder = ImUser::where('im_users.type', 1)->leftJoin('im_users as iu', 'iu.service_id', '=', 'im_users.id');

    $builder->whereIn('im_users.user_id',  function ($builder) {
      /** @var Builder $builder */
      $builder->from('admins');
      $builder->select(['admins.id']);
    });
    $builder->whereNotIn('im_users.user_id', function ($builder) {
      /** @var Builder $builder */
      $builder->from('brokers');
      $builder->select(['brokers.admin_id']);
    })->where('im_users.online', true);
    $pre = $builder->getConnection()->getTablePrefix();
    $builder->select([
      'im_users.*',
      \DB::raw("COUNT({$pre}iu.id) AS customs")
    ])->orderBy('customs', 'ASC');
    $builder->groupBy('im_users.id');

    return $builder->first();
  }

  public function generateImId()
  {
    return str_replace('.', '', uniqid('td_im_', true));
  }

  /**
   * @param null $userId
   * @param bool $service
   * @param array $info
   * @return \Illuminate\Database\Eloquent\Model|ImUser
   */
  public function findOrCreateUser($userId = null, $service = false, $info = [])
  {
    $type = $service ? 1 : 0;

    if ($userId) {
      $builder = ImUser::where(['user_id' => $userId, 'type' => $type]);
      if ($exists = $builder->first()) {
        $exists->online = true;
        $exists->save();
        return $exists;
      }
      $nick = '';
      if ($service) {
        $user = Service::find($userId);
        $nick = $user->name;
      } else {
        $user = User::find($userId);
        $user && $nick = $user->nick_name;
      }
      if (!array_get($info, 'nick') && $user) {
        $info['nick'] = $nick;
      }
      if (!array_get($info, 'icon_url') && $user && !$service) {
        $info['icon_url'] = $user->avatar;
      }
    } else if (($device = $this->getDeviceId()) && !$service) {
      $builder = ImUser::where(['device_id' => $device, 'type' => $type]);
      if ($exists = $builder->first()) {
        $exists->online = true;
        $exists->save();
        return $exists;
      }
    }
    return $this->createChatter($userId, $type, $info);
  }

  /**
   * @param ImUser $service
   * @param ImUser $chatter
   */
  public function serviceSayHello($service, $chatter)
  {
    $hello = env('SERVICE_HELLO', '您好,请问有什么可以帮助您的吗');
    $this->asyncSend($service, $chatter->con_id, $hello, Im::getTypeText(), [
      'attrs' => [
        'hello' => true
      ]
    ]);
  }

  /**
   * @param ImUser $service
   * @param ImUser $chatter
   */
  public function chatterSayHello($chatter, $service)
  {
    $nick = $chatter->nick ?: $chatter->im_id;
    $hello = $nick . '上线了';
    $this->asyncSend($chatter, $chatter->con_id, $hello, Im::getTypeText(), [
      'attrs' => [
        'hello' => true
      ]
    ]);
  }

  public function deleteChatter($id)
  {
    return \DB::transaction(function () use ($id) {
      if ($chatter = ImUser::find($id)) {
        Im::deleteUser($chatter->im_id);
        $chatter->delete();
      }
      return 1;
    });
  }

  public function contacts()
  {
    $service = $this->getService();
    $serviceId = $service->id;
    $builder = ImUser::with(['user' => function ($builder) {
      $builder->select([
        'id',
        'phone',
        'nick_name',
        'real_name',
        'avatar',
      ]);
    }])->where('type', 0)
      ->whereIn('user_id', function ($builder) use ($serviceId) {
        /** @var Builder $builder */
        $builder->from('user');
        $builder->select(['user.id']);
        $builder->rightJoin('brokers', 'brokers.id', '=', 'user.broker_id');
        $builder->where('brokers.admin_id', '=', $serviceId);
      });

    $builder->select(['im_users.id', 'im_users.im_id', 'im_users.con_id', 'im_users.nick', 'im_users.icon_url', 'im_users.user_id']);
    return $this->getChangeAblePage($builder);
  }


  /**
   * @param ImUser $user
   * @param $to
   * @param $summary
   * @param null $type
   * @param array $data
   */
  public function asyncSend($user, $to, $summary, $type = null, $data = [])
  {
    if ($type === null) {
      $type = Im::getTypeText();
    }
    if ($user) {
      dispatch(new PushImMessage($user, $to, $summary, $type, $data));
    }
  }

  public function disconnect($imId)
  {
    $serviceId = $this->getService()->id;
    \DB::transaction(function () use ($serviceId, $imId) {
      ChatterService::whereChatterId(function ($builder) use ($imId) {
        /** @var Builder $builder */
        $builder->from('im_users')->where('im_id', $imId)->select(['id'])->first();
      })->where('service_id', '=', function ($builder) use ($serviceId) {
        /** @var Builder $builder */
        $builder->from('im_users')->where('user_id', $serviceId)->where('type', 1)->select(['id'])->first();
      })->delete();
      $builder = ImUser::whereImId($imId)->where('type', 0);
      $builder->leftJoin('user', 'user.id', '=', 'im_users.user_id')
        ->select([
          'im_users.*',
          'user.broker_id'
        ]);

      if (($chatter = $builder->first())) {
//        if (!$chatter->broker_id) { // 没有broker 将当前客服和会话置空
        Im::disconnect($chatter->con_id);
        $chatter->update([
          'service_id' => null,
          'con_id'     => null
        ]);
//        }
      }
    });
  }

  /**
   * @param $userId
   * @param $type
   * @param $info
   * @return mixed
   */
  public function createChatter($userId, $type, $info = [])
  {
    $data = [
      'user_id'     => $userId,
      'type'        => $type,
      'im_id'       => $this->generateImId(),
      'im_password' => str_random(8),
      'nick'        => array_get($info, 'nick'),
      'icon_url'    => array_get($info, 'icon_url'),
      'online'      => true,
      'device_id'   => $this->getDeviceId()
    ];
    return \DB::transaction(function () use ($data, $info) {
      $id = $data['im_id'];
      if (!isset($info['nick'])) {
        $info['nick'] = $id;
      }
      Im::createUser($id, $data['im_password'], $info);
      return ImUser::create($data);
    });
  }

  public function conversions($chatterId)
  {
    $builder = ImUser::leftJoin('chatter_services', function (JoinClause $clause) {
      $clause->on('im_users.id', '=', 'chatter_services.chatter_id');
      $clause->on('im_users.service_id', '=', 'chatter_services.service_id');
    })->where('im_users.service_id', $chatterId)
      ->where('im_users.con_id', '!=', null)
      ->select(['im_users.id',
        'im_users.im_id', 'im_users.con_id',
        'im_users.nick', 'im_users.icon_url',
        'im_users.user_id', 'chatter_services.last_msg',
        'chatter_services.last_msg_at'])->orderBy('chatter_services.last_msg_at', 'desc')->orderBy('chatter_services.id', 'desc');

    return $this->getChangeAblePage($builder);
  }

  public function editService($data)
  {
    $service = $this->getService();

    $this->copy($service->imUser, $data);

    return $service;
  }

  public function getImUser($imIds)
  {
    $builder = ImUser::with(['user' => function ($builder) {
      $builder->select([
        'id',
        'phone',
        'nick_name',
        'real_name',
        'avatar',
      ]);
    }])->whereIn('im_id', (array)$imIds);
    $builder->select(['im_users.id', 'im_users.im_id', 'im_users.con_id', 'im_users.nick', 'im_users.icon_url', 'im_users.user_id']);

    return $builder->get()->toArray();
  }

  public function editImUser($data)
  {
    $imId = $this->getChatterImId();

    if ($chatter = ImUser::whereImId($imId)->first()) {
      $chatter->service;
      return $this->copy($chatter, $data);
    }

    throw new AuthenticationException();
  }

  public function messages(\Closure $then = null)
  {
    $service = $this->getService();
    $builder = ImMessage::whereSenderId($service->imUser->id);
    $builder->select([
      'im_messages.id',
      'im_messages.type',
      'im_messages.summary',
      'im_messages.msg',
      'im_messages.created_at'
    ])->with(['receivers' => function ($builder) {
      /** @var Builder $builder */
      $builder->rightJoin('im_users', 'receiver_id', '=', 'im_users.id')
        ->select([
          'im_receivers.*',
          'im_users.im_id',
          'im_users.id',
          'im_users.con_id',
          'im_users.nick',
          'im_users.icon_url'
        ]);
    }]);
    $then && $then($builder);
    $builder->orderBy('im_messages.created_at', 'desc')->groupBy('im_messages.id');
    return $this->getChangeAblePage($builder);
  }

  public function deleteMessage($id)
  {
    \DB::transaction(function () use ($id) {
      $service = $this->getService();
      $msg = ImMessage::find($id);

      if (!$msg || $msg->sender_id != $service->imUser->id) {
        return $this->respondNotFound('消息不存在');
      }
      ImReceiver::whereImMessageId($id)->delete();
      $msg->delete();
      return true;
    });
  }
}