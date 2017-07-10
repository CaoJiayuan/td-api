<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-3-22
 * Time: 下午5:27
 */

namespace App\Http\Controllers\V1;


use App\Entity\ImUser;
use App\Entity\Service;
use App\Http\Controllers\Controller;
use App\Jobs\AssignImUsers;
use App\Repositories\ServiceRepository;
use App\Traits\ApiAuthenticate;
use App\Traits\ServiceAuthenticated;
use Illuminate\Http\Request;

class ServiceController extends Controller
{

  use ApiAuthenticate, ServiceAuthenticated;

  protected $loginUsername = 'mobile';

  protected $alternativeUsername = 'email';

  public function contacts(ServiceRepository $repository)
  {
    return $repository->contacts();
  }

  public function send(ServiceRepository $repository, $type = null)
  {
    $data = $this->getValidatedData([
      'to' => 'required',
    ], [
      'to.required' => '发送会话不能为空'
    ]);
    list($t, $msgData) = $this->getMessageByType($type);
    $data = array_merge($data, $msgData);
    $user = $this->getService()->imUser;
    $repository->asyncSend($user, $data['to'], $data['content'], $t, $msgData);
    return $this->respondSuccess('发送成功');
  }

  public function getMessageByType($type = null)
  {
    if (!$type) {
      $type = 'text';
    }
    switch ($type) {
      case 'text' :
        $data = $this->getValidatedData([
          'content' => 'required',
        ]);
        $t = \Im::getTypeText();
        break;
      case  'img' :
        $file = $this->getValidatedData([
          'url' => 'required',
          'objId',
        ]);
        $data['file'] = $file;
        $data['content'] = '图像消息';
        $meta = $this->getInputData(['name', 'format', 'height' => 'int', 'width' => 'int', 'size' => 'int']);
        $data['file']['metaData'] = $meta;
        $t = \Im::getTypeImg();
        break;
      case  'voice' :
        $file = $this->getValidatedData([
          'url' => 'required',
          'objId',
        ]);
        $data['file'] = $file;
        $data['content'] = '音频消息';
        $data['file']['metaData'] = $this->getInputData(['name', 'format', 'duration' => 'int', 'size' => 'int']);
        $t = \Im::getTypeVoice();
        break;
      case  'video' :
        $file = $this->getValidatedData([
          'url' => 'required',
          'objId',
        ]);
        $data['file'] = $file;
        $data['content'] = '视频消息';
        $data['file']['metaData'] = $this->getInputData(['name', 'format', 'duration' => 'int', 'size' => 'int']);
        $t = \Im::getTypeVideo();
        break;
      case  'file' :
        $file = $this->getValidatedData([
          'url' => 'required',
        ]);
        $data['file'] = $file;
        $data['content'] = '文件';
        $data['file']['metaData'] = $this->getInputData(['name', 'size' => 'int']);
        $t = \Im::getTypeFile();
        break;
      case 'pos' :
        $loc = $this->getValidatedData([
          'longitude' => 'required',
          'latitude'  => 'required',
        ]);
        $loc['longitude'] = (float)$loc['longitude'];
        $loc['latitude'] = (float)$loc['latitude'];
        $data['content'] = '定位';
        $data['loc'] = $loc;
        $t = \Im::getTypePos();
        break;
      default:
        return $this->respondNotFound();
    }
    return [$t, $data];
  }

  public function matchService(ServiceRepository $repository, $id)
  {
    $chatter = ImUser::findOrFail($id);
    $repository->matchService($chatter, true);
    $chatter->service;
    return $this->respondWithItem($chatter);
  }

  public function validateLogin(Request $request)
  {
    $this->validate($request, [
      'password' => 'required',
    ]);
  }

  public function loginChatter(ServiceRepository $repository)
  {
    $data = \Request::all();
    return $this->respondWithItem($repository->loginChatter($data));
  }

  public function sendLoginSuccess(Service $user, $request)
  {
    $user->token = \JWTAuth::fromUser($user);
    $chatter = (new ServiceRepository)->findOrCreateUser($user->id, true, [
      'nick' => $user->name
    ]);
    $user->im_user = $chatter;
    return $this->respondWithItem($user);
  }

  public function disconnect(ServiceRepository $repository)
  {
    $data = $this->getValidatedData(['im_id' => 'required']);

    $repository->disconnect($data['im_id']);

    return $this->respondSuccess('删除聊天成功');
  }

  public function getAccount()
  {
    $service = $this->getService();
    $service->imUser;
    return $this->respondWithItem($service);
  }

  public function beforeAttempt($user)
  {
    \DB::setDefaultConnection('protoss');
    $service = \DB::table('role_admin')->leftJoin('roles', 'roles.id', '=', 'role_admin.role_id')
      ->whereIn('roles.name', [config('entrust.service.name', 'service'), config('entrust.broker.name', 'broker')])
      ->where('role_admin.admin_id', $user->id)
      ->exists();
    if (!$service) {
      return $this->respondForbidden('请使用客服或客户经理帐号登陆该系统');
    }
    return true;
  }

  public function editAccount(ServiceRepository $repository, Request $request)
  {
    $data = $request->only(['nick', 'icon_url']);
    $service = $repository->editService($data);
    return $this->sendLoginSuccess($service, $request);
  }

  public function logout()
  {
    $service = $this->getService();
    $service->offLine();

    return $this->respondSuccess('登出成功');
  }

  public function conversions(ServiceRepository $repository)
  {
    $service = $this->getService();
    $imUser = $service->imUser;
    return $repository->conversions($imUser ? $imUser->id : 0);
  }


  public function getImUser(ServiceRepository $repository)
  {
    $data = $this->getValidatedData([
      'im_ids' => 'required'
    ]);

    return $repository->getImUser($data['im_ids']);
  }

  public function editImUser(ServiceRepository $repository, Request $request)
  {
    $data = $request->only(['nick', 'icon_url']);
    $chatter = $repository->editImUser($data);
    return $this->respondWithItem($chatter);
  }

  public function groupMessages(ServiceRepository $repository)
  {
    return $repository->messages(function ($builder) {
      $builder->has('receivers', '>', 1);
    });
  }

  public function assignImUsers()
  {
    dispatch(new AssignImUsers);
    return $this->respondSuccess();
  }

  public function deleteGroupMessage(ServiceRepository $repository, $id)
  {
    $repository->deleteMessage($id);
    return $this->respondSuccess('删除成功');
  }
}