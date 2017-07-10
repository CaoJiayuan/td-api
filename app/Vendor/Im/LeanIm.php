<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-3-24
 * Time: 上午10:16
 */

namespace App\Vendor\Im;

use App\Entity\LeanChatter;
use App\Vendor\Im\Contracts\Im;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise;
use GuzzleHttp\RequestOptions;

class LeanIm implements Im
{

  protected $otherOptions = [];
  protected $headers = [];

  const API_BASE_URL = 'https://api.leancloud.cn/';
  const API_VERSION = '1.1';
  const MSG_TYPE_TEXT = -1;
  const MSG_TYPE_IMG = -2;
  const MSG_TYPE_VOICE = -3;
  const MSG_TYPE_VIDEO = -4;
  const MSG_TYPE_POS = -5;
  const MSG_TYPE_FILE = -6;
  const TRANSIENT = false;

  public function getClient()
  {
    $h = array_merge($this->headers,
      [
        'X-LC-ID'  => config('leancloud.id'),
        'X-LC-Key' => config('leancloud.master_key') . ',master',
        'Accept'   => 'application/json',
      ]);
    $opts = [
      'headers'     => $h,
      'http_errors' => false,
      'verify'      => false,
    ];

    return new Client($opts);
  }


  public function sendMessage($form, $toCon, $content, $type = LeanIm::MSG_TYPE_TEXT, $data = [])
  {
    if (is_array($toCon)) {
      return $this->sendToMulti($form, $toCon, $content, $type, $data);
    }

    return $this->post('rtm/messages', 'json', $this->getMessage($form, $toCon, $type, $content, $data));
  }

  public function getMessage($form, $toCon, $type, $content, $data = [])
  {
    $attrs = array_get($data, 'attrs');
    if (!array_get($attrs, 'hello')) {
      $attrs['hello'] = false;
    }
    $message = [
      '_lctype'  => $type,
      '_lctext'  => $content,
      '_lcattrs' => $attrs,
    ];

    if ($file = array_get($data, 'file')) {
      $message['_lcfile'] = $file;
    }
    if ($loc = array_get($data, 'loc')) {
      $message['_lcloc'] = $loc;
    }

    $data = [
      'from_peer' => $form,
      'conv_id'   => $toCon,
      'transient' => static::TRANSIENT,
      'message'   => $message
    ];

    return $data;
  }

  /**
   * @param $form
   * @param array $toCons
   * @param $content
   * @param int $type
   * @param array $data
   * @return mixed
   */
  public function sendToMulti($form, array $toCons, $content, $type = LeanIm::MSG_TYPE_TEXT, $data = [])
  {
    $client = $this->getClient();
    $promises = [];

    foreach (array_unique($toCons) as $con) {
      $params = $this->getMessage($form, $con, $type, $content, $data);
      $promises[$con] = $client->postAsync(static::API_BASE_URL . static::API_VERSION . '/rtm/messages', [
        'json' => $params
      ]);
    }
    return Promise\settle($promises)->otherwise(function ($e) {
      /** @var RequestException $e */
      \Log::info('>>>>>>>>>>>>Rejected>>>>>>', [
        'code' => $e->getCode(),
        'msg'  => $e->getMessage(),
      ]);
    })->wait();
  }

  public function createUser($id, $password, $info = [])
  {
    return [
      'id'       => $id,
      'password' => $id,
      'info'     => $info
    ];
  }

  public function deleteUser($id)
  {

  }

  public function createConversion($chatters = [], $name = null)
  {
    return $this->post('classes/_Conversation', 'json', [
      'name' => $name ?: $this->generateConName(),
      'm'    => $chatters
    ]);
  }

  public function getMsgByUserId($id)
  {
    return $this->get('/rtm/messages/history', 'query', [
      'from' => $id
    ]);
  }

  public function getMsgByConId($conId)
  {
    return $this->get('/rtm/messages/history', 'query', [
      'convid' => $conId
    ]);
  }

  public function generateConName()
  {
    return str_replace('.', '', uniqid('td_con_', true));
  }

  public function get($uri, $dataType = RequestOptions::QUERY, $data = [])
  {
    return $this->exec($uri, 'GET', [
      $dataType => $data
    ]);
  }

  public function post($uri, $dataType = RequestOptions::JSON, $data = [])
  {
    return $this->exec($uri, 'POST', [
      $dataType => $data
    ]);
  }

  public function put($uri, $dataType = RequestOptions::JSON, $data = [])
  {
    return $this->exec($uri, 'PUT', [
      $dataType => $data
    ]);
  }

  public function delete($uri, $dataType = RequestOptions::JSON, $data = [])
  {
    $opts = $data ? [
      $dataType => $data
    ] : [];
    return $this->exec($uri, 'DELETE', $opts);
  }

  /**
   * @param $uri
   * @param $method
   * @param array $options
   * @return array|\Psr\Http\Message\StreamInterface|string
   */
  public function exec($uri, $method, $options = [])
  {
    $uri = ltrim($uri, '/');
    $url = static::API_BASE_URL . static::API_VERSION . '/' . $uri;
    $response = $this->getClient()->request($method, $url, $options);
    return $this->parseResponse($response);
  }

  /**
   * @param $response \Psr\Http\Message\ResponseInterface
   * @return \Psr\Http\Message\StreamInterface|array|string
   */
  public function parseResponse($response)
  {
    $body = $response->getBody();
    if (str_contains($response->getHeaderLine('Content-Type'), 'json')) {
      return json_decode($body, true);
    }
    $statusCode = $response->getStatusCode();
    if ($statusCode != 200) {
      \Log::info('>>>>>>>>>>>>Rejected>>>>>>', [
        'code' => $statusCode,
        'msg'  => $body,
      ]);
    }
    return $body;
  }

  public function disconnect($conId)
  {
    $aId = (array)$conId;

    $requests = [];
    foreach ($aId as $id) {
      $requests[] = $this->getRequestItem('DELETE', '/' . static::API_VERSION . '/classes/_Conversation/' . $id);
    }

    return $this->batch($requests);
  }

  public function getRequestItem($method, $path, $body = [])
  {
    $data = [
      'method' => $method,
      'path'   => $path
    ];
    if ($body) {
      $data['body'] = $body;
    }
    return $data;
  }

  public function batch($requests)
  {
    return $this->post('/batch', 'json', [
      'requests' => $requests
    ]);
  }

  public function getTypeText()
  {
    return static::MSG_TYPE_TEXT;
  }

  public function getTypeImg()
  {
    return static::MSG_TYPE_IMG;

  }

  public function getTypeVoice()
  {
    return static::MSG_TYPE_VOICE;
  }

  public function getTypePos()
  {
    return static::MSG_TYPE_POS;
  }

  public function getTypeVideo()
  {
    return static::MSG_TYPE_VIDEO;
  }

  public function getTypeFile()
  {
    return static::MSG_TYPE_FILE;
  }

  public function migrate()
  {
    \LeanCloud\Client::initialize(config('leancloud.id'), config('leancloud.key'), config('leancloud.master_key'));
    LeanChatter::registerClass();
    $chatter = new LeanChatter();
    $chatter->set('nick', 'adwadawd');
    return $chatter->save();
  }
}