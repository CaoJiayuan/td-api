<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-3-22
 * Time: 上午10:35
 */

namespace App\Repositories;


use App\Entity\Ad;
use App\Entity\Feedback;
use App\Traits\Authenticated;
use App\Traits\PageAble;

class InfoRepository
{

  use Authenticated, PageAble;

  public function ads(\Closure $closure = null)
  {
    $builder = Ad::where('ads.status', '=', 1);
    $builder->select(['ads.id', 'ads.title', 'ads.img', 'ads.type', 'ads.url', 'ads.ad_position_id as position']);
    $closure && $closure($builder);
    return $builder->get()->toArray();
  }

  public function randomAds($pUp, $pdown)
  {
    return $this->orderThenGroup($pUp, $pdown, 'rand()');
  }

  public function orderThenGroup($pUp, $pdown, $order, $limit = null)
  {
    $limit && $limit = 'limit ' . $limit;
    $sql = <<<SQL
SELECT * FROM
(
  SELECT `id`, `title`, `img`, `type`, `url`, `ad_position_id` AS `position` 
  FROM `protoss_ads` 
  WHERE ad_position_id BETWEEN :up AND :down AND `deleted_at` is null AND `status` != 0 
  ORDER BY $order
) ran
GROUP BY position ORDER BY position ASC $limit
SQL;
    $con = \DB::connection('protoss');
    $result = [];
    object_to_array($con->select($sql, [$pUp, $pdown]), $result);
    return $result;
  }

  public function feedback($data)
  {
    return Feedback::create($data);
  }

  public function myFeedback()
  {
    $userId = $this->getUserId();
    $builder = Feedback::where('user_id', $userId)->orderBy('id', 'desc');
    return $this->getChangeAblePage($builder);
  }


}