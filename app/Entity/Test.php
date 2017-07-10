<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-1-16
 * Time: 上午11:17
 */

namespace App\Entity;

class Test
{

  private $data = null;

  public function all()
  {
    if (!$this->data) {
      $arr = $this->getApiData();

      $this->data = $this->reformatData($arr);
    }
    return $this->data;
  }

  public function reformatData($data)
  {
    $d = [];
    foreach ($data as $item) {
      $result = array_only((array)$item, ['id', 'question']);
      $result['answers'] = $this->getAnswers($item);
      $d[] = $result;
    }

    return $d;
  }

  public function getAnswers($item)
  {
    $answers = [];

    foreach ($item as $key => $value) {
      if (strlen($key) == 1 && $value) {
        $answers[] = [
          'key'    => strtoupper($key),
          'answer' => $value,
          'score'  => array_get((array)$item, $key . '_score', 0)
        ];
      }
    }

    return $answers;
  }

  /**
   * @return mixed
   */
  public function getApiData()
  {
    \DB::setDefaultConnection('protoss');
    $arr = \DB::table('risk_question')->get();
    return $arr;
  }
}