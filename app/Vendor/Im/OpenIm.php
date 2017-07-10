<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-3-24
 * Time: 上午10:10
 */

namespace App\Vendor\Im;

use App\Vendor\Im\Contracts\Im;

class OpenIm implements Im
{
  const MSG_TYPE_TEXT = 0;
  const MSG_TYPE_IMG = 1;
  const MSG_TYPE_VOICE = 2;
  const MSG_TYPE_POS = 8;

  public function getClient()
  {
    $c = new \TopClient(config('topsdk.app_key'), config('topsdk.secret'));
    $c->format = 'json';
    return $c;
  }

  public function sendCustomMessage($form, $to, $summary, $data = [])
  {
    $req = new \OpenimCustmsgPushRequest;
    $custmsg = new \CustMsg;
    $custmsg->from_user = $form;
    $custmsg->to_users = "$to";
    $custmsg->summary = $summary;
    $custmsg->data = $data;
    $custmsg->aps = "{\"alert\":\"$summary\"}";
    $custmsg->apns_param = $data;
    $custmsg->invisible = "0";
    $custmsg->from_nick = $form;
    $custmsg->from_taobao = "0";
    $req->setCustmsg(json_encode($custmsg));
    return static::exec($req);
  }

  public function sendMessage($form, $to, $content, $type = OpenIm::MSG_TYPE_TEXT, $data = [])
  {
    $req = new \OpenimImmsgPushRequest;
    $immsg = new \ImMsg;
    $immsg->from_user = $form;
    $immsg->to_users = "$to";
    $immsg->msg_type = "$type";
    $immsg->context = $content;
    $immsg->media_attr = "{\"type\":\"amr\",\"playtime\":6}";
    $req->setImmsg(json_encode($immsg));
    return $this->exec($req);
  }


  public function exec($req)
  {
    $result = [];
    object_to_array($this->getClient()->execute($req), $result);
    return $result;
  }

  /**
   * @param $id
   * @param $password
   * @param array $info
   * nick
   * icon_url
   * email
   * mobile
   * taobaoid
   * remark
   * extra
   * career
   * vip
   * address
   * name
   * age
   * gender
   * wechat
   * qq
   * weibo
   * @return array
   */
  public function createUser($id, $password, $info = [])
  {
    $req = new \OpenimUsersAddRequest;
    $userInfo = new \Userinfos;
    $userInfo->userid = $id;
    $userInfo->password = $password;
    foreach ($info as $key => $value) {
      if (property_exists(\Userinfos::class, $key)) {
        $userInfo->$key = $value;
      }
    }
    $req->setUserinfos(json_encode($userInfo));
    return $this->exec($req);
  }

  public function getUser($id)
  {
    $id = implode(',', (array)$id);
    $req = new \OpenimUsersGetRequest;
    $req->setUserids($id);

    return $this->exec($req);
  }

  public function deleteUser($id)
  {
    $id = implode(',', (array)$id);
    $req = new \OpenimUsersDeleteRequest;
    $req->setUserids($id);
    return $this->exec($req);
  }

  public function createConversion($chatters = [], $name = null)
  {
    return [];
  }

  public function disconnect($conId)
  {

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
    throw new \UnexpectedValueException('不支持的消息类型');
  }

  public function getTypeFile()
  {
    throw new \UnexpectedValueException('不支持的消息类型');
  }
}