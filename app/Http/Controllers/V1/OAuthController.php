<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-4-5
 * Time: 上午10:23
 */

namespace App\Http\Controllers\V1;


use App\Http\Controllers\Controller;
use App\Utils\OAuth;

class OAuthController extends Controller
{
  protected $authUrls = [
    'qq'     => 'https://graph.qq.com/oauth2.0/authorize',
    'weibo'  => 'https://api.weibo.com/oauth2/authorize',
    'weixin' => 'https://open.weixin.qq.com/connect/qrconnect',
  ];
  protected $tokenUrls = [
    'qq'     => 'https://graph.qq.com/oauth2.0/token',
    'weibo'  => 'https://api.weibo.com/oauth2/access_token',
    'weixin' => 'https://api.weixin.qq.com/sns/oauth2/access_token',
  ];

  public function login($type)
  {
    if (!in_array($type, ['qq', 'weibo', 'weixin'])) {
      return $this->respondNotFound();
    }
    $data = OAuth::getAuth($type);

    return view('oauth.success', $data);
  }

  public function page($type)
  {
    if (!in_array($type, ['qq', 'weibo', 'weixin'])) {
      return $this->respondNotFound();
    }

    return OAuth::goPage($type);
  }
}