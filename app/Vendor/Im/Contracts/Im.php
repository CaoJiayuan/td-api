<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-3-24
 * Time: 上午10:11
 */

namespace App\Vendor\Im\Contracts;


interface Im
{
  public function getClient();

  public function sendMessage($form, $to, $content, $type = 0, $data = []);

  public function createUser($id, $password, $info = []);

  public function deleteUser($id);

  public function createConversion($chatters = [], $name = null);

  public function disconnect($conId);

  public function getTypeText();

  public function getTypeImg();

  public function getTypeVoice();

  public function getTypePos();

  public function getTypeVideo();
  public function getTypeFile();
}