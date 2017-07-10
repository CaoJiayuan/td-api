<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-3-13
 * Time: 下午4:16
 */

namespace App\Utils;


use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class ProtossApi
{

  public static function getClient(array $options = [])
  {
    $opt = [
      'http_errors' => false,
      'headers'     => [
        'Accept' => 'application/json',
      ],
    ];
    $opt = $opt + $options;
    $c = new Client($opt);
    return $c;
  }

  public static function licenses($name)
  {
    return static::get('licenses/' . $name);
  }

  /**
   * @param Response|ResponseInterface $response
   * @return \Symfony\Component\HttpFoundation\Response|Response
   */
  public static function toIlluminateResponse($response)
  {
    /** @var \Illuminate\Http\Response $res */
    $res = response($response->getBody(), $response->getStatusCode(), array_except($response->getHeaders(), 'Transfer-Encoding'));
    $statusTexts = \Symfony\Component\HttpFoundation\Response::$statusTexts;
    (!$res->isSuccessful() && env('APP_ENV') == 'local') && \Log::error(array_get($statusTexts, $res->getStatusCode(), 'Unknown') . ':' . $res->getContent());

    return $res;
  }

  public static function post($uri, $data = [])
  {
    $uri = ltrim($uri,'/');

    $response = static::getClient()->post(env('PROTOSS_API_ADDRESS', 'http://112.74.246.68:8111') . '/api/' . $uri, ['form_params' => $data]);

    return static::toIlluminateResponse($response);
  }

  public static function get($uri, $data = [])
  {
    $uri = ltrim($uri,'/');

    $response = static::getClient()->get(env('PROTOSS_API_ADDRESS', 'http://112.74.246.68:8111') . '/api/' . $uri, ['query' => $data]);

    return static::toIlluminateResponse($response);
  }
}