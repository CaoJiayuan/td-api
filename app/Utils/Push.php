<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-4-5
 * Time: 上午9:35
 */

namespace App\Utils;


use App\Vendor\Push\LeanPush;
use Illuminate\Support\Facades\Facade;

class Push extends Facade
{
  public static function getFacadeAccessor()
  {
    return LeanPush::class;
  }
}