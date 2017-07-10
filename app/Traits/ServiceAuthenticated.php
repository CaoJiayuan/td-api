<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-3-23
 * Time: 上午9:23
 */

namespace App\Traits;


use App\Entity\Service;

trait ServiceAuthenticated
{
  /**
   * @return Service
   */
  public function getService()
  {
    return \Auth::user();
  }
}