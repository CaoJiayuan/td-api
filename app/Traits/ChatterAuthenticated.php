<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-3-29
 * Time: 下午2:30
 */

namespace App\Traits;


use Illuminate\Auth\AuthenticationException;

trait ChatterAuthenticated
{
  public function getChatterImId($throw = true)
  {
    $imId = \Request::get('im_id');
    if (!$imId && $throw) {
      throw new AuthenticationException();
    }

    return $imId;
  }
}