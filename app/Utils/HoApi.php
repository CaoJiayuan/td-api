<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-4-14
 * Time: 下午3:14
 */

namespace App\Utils;


use App\Traits\GuzzleHelper;
use GuzzleHttp\RequestOptions;

class HoApi
{
  use GuzzleHelper;

  protected $apiBaseUrl = 'http://www.hoapi.com/';

  protected $apiVersion = '';

  public function getFiltered($content)
  {
    $data = $this->post('index.php/Home/Api/check', RequestOptions::FORM_PARAMS, [
      'str' => $content,
      'token' => '597ddda1f8b2e2a42e78c81ded94c426'
    ]);

    return array_get($data, 'data.new_str', $content);
  }
}