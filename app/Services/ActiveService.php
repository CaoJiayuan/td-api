<?php
/**
 *
 * User: YuGang Yang
 * Date: 10/10/15
 * Time: 14:46
 */

namespace App\Services;

use Illuminate\Support\Facades\URL;

class ActiveService
{

  public function isActive($uri)
  {
    $url = URL::current();
    $urls = explode('/',$url);
    foreach($urls as $v)
    {
      if($uri==$v)
      {
        echo 'active';
      }else{
        echo '';
      }
    }
  }
}