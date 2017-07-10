<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-3-30
 * Time: 下午2:32
 */

namespace App\Vendor\Push;


use App\Traits\GuzzleHelper;
use App\Vendor\Push\Contacts\Push;

class LeanPush implements Push
{
  use GuzzleHelper;

  protected $apiBaseUrl = 'https://api.leancloud.cn/';

  protected $apiVersion = '1.1';

  public function single($id, $title, $content, $data = [])
  {
    return $this->batch((array)$id, $title, $content, $data);
  }

  public function broadcast($title, $content, $data = [])
  {
    return $this->post('push', 'json', [
      'data'  => $this->getPushData($title, $content, $data)
    ]);
  }

  public function batch(array $ids, $title, $content, $data = [])
  {
    return $this->post('push', 'json', [
      'where' => [
        'installationId' => [
          '$in' => $ids
        ]
      ],
      'data'  => $this->getPushData($title, $content, $data)
    ]);
  }

  public function getPushData($title, $content, $data)
  {
    return [
      'ios'     => $this->getIosPushData($title, $content, $data),
      'android' => $this->getAndroidPushData($title, $content, $data)
    ];
  }

  public function getIosPushData($title, $content, $data)
  {
    $push = [
      'alert' => $content,
      'badge' => 1,
    ];

    return array_merge($data, $push);
  }

  public function getAndroidPushData($title, $content, $data)
  {
    $push = [
      'title' => $title,
      'alert' => $content,
      'badge' => 'com.jsjssy.push',
      'action' => 1,
    ];

    return array_merge($data, $push);
  }
}