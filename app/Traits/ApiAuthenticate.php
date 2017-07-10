<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-3-22
 * Time: 下午5:36
 */

namespace App\Traits;


use App\Entity\Service;
use Illuminate\Http\Request;

trait ApiAuthenticate
{

  public function login(Request $request)
  {
    $this->validateLogin($request);
    if ($user = $this->attempt($request)) {
      return $this->sendLoginSuccess($user, $request);
    }

    return $this->respondUnprocessable('用户名或密码错误');
  }

  /**
   * @param Request $request
   * @return bool|Service
   */
  public function attempt(Request $request)
  {
    $credentials = $this->credentials($request);
    $user = null;
    if ($credentials[$this->loginUsername]){
      $builder = Service::where($this->loginUsername, trim($credentials[$this->loginUsername]));
      $user = $builder->first();
    }
    if (!$user && $this->alternativeUsername) {
      $user = Service::where($this->alternativeUsername, trim($credentials[$this->alternativeUsername]))->first();
    }
    if ($user) {
      if (method_exists($this, 'beforeAttempt')) {
        $this->beforeAttempt($user);
      }
      if (\Hash::check($credentials['password'], $user->password)) {
        return $user;
      }
      return false;
    }
    return false;
  }

  public function sendLoginSuccess(Service $user, $request)
  {
    $user->token = \JWTAuth::fromUser($user);
    return $this->respondWithItem($user);
  }

  protected function credentials(Request $request)
  {
    return $request->only($this->loginUsername, 'password', $this->alternativeUsername);
  }
}