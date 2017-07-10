<?php
namespace App\Traits;

trait CurlTrait
{
  public function getData($url,$method='get',$data=null)
  {
    $ch = curl_init($url);
//    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
//    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    if(strtolower($method)=='post')
    {
      curl_setopt($ch,CURLOPT_POST,true);
    }
    if(!empty($data))
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $data = curl_exec($ch);
    curl_close($ch);
    return json_decode($data,true);
  }
}