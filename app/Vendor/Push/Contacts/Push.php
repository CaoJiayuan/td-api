<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-3-30
 * Time: 下午2:33
 */

namespace App\Vendor\Push\Contacts;


interface Push
{
  public function single($id, $title, $content, $data = []);

  public function broadcast($title, $content, $data = []);

  public function batch(array $ids, $title, $content, $data = []);
}