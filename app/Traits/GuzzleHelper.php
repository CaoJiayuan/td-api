<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-4-5
 * Time: 上午9:40
 */

namespace App\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

trait GuzzleHelper
{
  protected $headers = [];


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
    $url = $this->apiBaseUrl . $this->apiVersion . '/' . $uri;
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
    if ($json = json_decode($body, true)) {
      return $json;
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
}