<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-3-21
 * Time: 上午10:56
 */

namespace App\Utils;


use App\Vendor\Im\LeanIm;
use Illuminate\Support\Facades\Facade;

class Im extends Facade
{

  public static function getFacadeAccessor()
  {
    return LeanIm::class;
  }
}