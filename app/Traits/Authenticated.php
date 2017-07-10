<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-3-15
 * Time: 下午5:27
 */

namespace App\Traits;


use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait Authenticated
{
  public function getUserId($throw = true)
  {
    $userId = \Request::get('user_id', 0);
    if (!$userId && $throw) {
      throw new AuthenticationException();
    }
    return $userId ?: 0;
  }

  public function getDeviceId($throw = false)
  {
    $deviceId = \Request::header('X-Client');
    if (!$deviceId && $throw) {
      throw new HttpException(403, 'Access denied!');
    }
    return $deviceId;
  }
}