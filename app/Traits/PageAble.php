<?php
/**
 * PageAble.php
 * Date: 16/9/20
 * Time: 下午2:45
 * Created by Caojiayuan
 */

namespace App\Traits;


use Api\StarterKit\Traits\ParamTraits;
use Api\StarterKit\Utils\Constants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

trait PageAble
{

  /**
   * @param Builder|Model $builder
   * @return Builder
   */
  public function morphKey($builder)
  {
    $maxId = \Request::get(Constants::getParameterKeyMaxId(), 0);
    $sinceId = \Request::get(Constants::getParameterKeySinceId(), 0);
    $size = \Request::get(Constants::getParameterKeyPageSize(), 20);
    if ($maxId) {
      $builder->where('id', '<', $maxId);
    } else {
      if ($sinceId) {
        $builder->where('id', '>', $sinceId);
      }
    }
    
    $builder->take($size);
    return $builder;
  }

  /**
   * @param Builder|Model $builder
   * @return Builder
   */
  public function morphPage($builder)
  {
    $page = \Request::get(config('consts.ParameterPage', 'page'), 1);

    $size = \Request::get(config('consts.ParameterPageSize', 'page_size'), 20);

    $builder->forPage($page, $size);

    return $builder;
  }

  /**
   * @param Builder|Model $builder
   * @return Builder|Collection|AbstractPaginator
   */
  public function getChangeAblePage($builder)
  {
    if (\Request::get('web')) {
      $perPage = \Request::get('per_page', 20);
      $url = url()->current();

      $query = \Request::query();
      $query = http_build_query(array_except($query, 'page'));
      $path = $url . '?' . $query;

      return $builder->paginate($perPage)->setPath($path);
    }
    $this->morphPage($builder);

    return $builder->get()->toArray();
  }
}