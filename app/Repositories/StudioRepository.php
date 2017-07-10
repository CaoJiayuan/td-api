<?php
/**
 * NewsRepository.php
 * Date: 2016/10/12
 * Time: 下午2:44
 * Created by Caojiayuan
 */

namespace App\Repositories;


use App\Entity\News;
use App\Entity\Studio;
use App\Traits\PageAble;

class StudioRepository
{
  use PageAble;

  public function getList(\Closure $after = null)
  {
    $builder = Studio::with('channels','anchors')
      ->whereEnable(true)
      ->orderBy('updated_at', 'desc');

    $this->morphKey($builder);

    $builder->select([
      'id',
      'type',
      'title',
      'status',
      'vip',
    ]);

    if ($after !== null) {
      $after($builder);
    } 
    
    return $builder->get();
  }
}