<?php
/**
 * NewsRepository.php
 * Date: 2016/10/12
 * Time: 下午2:44
 * Created by Caojiayuan
 */

namespace App\Repositories;


use App\Entity\Area;
use App\Entity\Calendar;
use App\Entity\Event;
use App\Traits\ModelHelper;
use App\Traits\PageAble;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class CalendarRepository
{
  use PageAble, ModelHelper;

  public function __construct()
  {
    \DB::setTablePrefix('');
  }

  /**
   * @param $date
   * @param null $indr
   * @param null $area
   * @param null $web
   * @param \Closure|null $after
   * @return \App\Entity\Calendar[]|Collection|mixed
   */
  public function getList($date, $indr = null, $area = null, $web = null, \Closure $after = null)
  {
    /** @var Builder $builder */
    $builder = Calendar::leftJoin('PUB_AREA_CODE', 'PUB_AREA_CODE.area_uni_code', '=', 'contry_area');

    $builder->leftJoin('GLOB_ECO_INDR_INFO', 'GLOB_ECO_INDR_INFO.ECO_INDR_UNI_CODE', '=', 'GLOB_ECO_INDR_VIEW.eco_indr_uni_code');

    if ($indr) {
      $builder->where('GLOB_ECO_INDR_VIEW.eco_indr_uni_code', '=', $indr);
    }

    if (!$indr && $date) {
      $time = Carbon::createFromTimestamp($date);
      $time2 = Carbon::createFromTimestamp($date - 1)->addDay();
      $builder->whereBetween('GLOB_ECO_INDR_VIEW.decl_date', [$time, $time2]);
    }

    if ($area) {
      $builder->whereIn('GLOB_ECO_INDR_VIEW.contry_area', (array)$area);
    }

    if ($filter = \Request::get('filter')) {
      $this->likeOr($builder, [
        'indr_name',
        'PUB_AREA_CODE.area_chi_name',
      ], $filter);
    }

    $domain = url('/');
    $builder->select([
      'indr_name as title',
      'GLOB_ECO_INDR_INFO.DATE_INTPT as interpretation',
      'GLOB_ECO_INDR_INFO.ATTON_REAS as attention',
      'GLOB_ECO_INDR_INFO.PROM_FREQU as frequency',
      'GLOB_ECO_INDR_INFO.STST_METHOD as stst_method',
      'GLOB_ECO_INDR_INFO.PROM_MECSM as mechanism',
      'GLOB_ECO_INDR_INFO.DATA_INFL as effect',
      'GLOB_ECO_INDR_VIEW.eco_indr_uni_code',
      'PUB_AREA_CODE.area_uni_code',
      'GLOB_ECO_INDR_VIEW.next_time',
      'perd_value as value_before',
      'forst_value as value_predict',
      'pub_value as value',
      'decl_date as publish_at',
      'impot_level as importance',
      'PUB_AREA_CODE.area_chi_name as area_name',
      \DB::raw('(decl_date-CURRENT_TIMESTAMP) as second'),
      \DB::raw("CONCAT('$domain/flags/',PUB_AREA_CODE.area_chi_name,'.png') as flag"),
    ]);

    if ($after !== null) {
      $after($builder);
    }
    $builder->orderBy('GLOB_ECO_INDR_VIEW.decl_date');
    $data = $builder->get()->toArray();
    return $data;
  }

  public function coming()
  {
    return $this->getList(null, null, null, null, function ($builder) {
      /** @var Builder $builder */
      $builder->having('second', '>', 0);
      $builder->orderBy('GLOB_ECO_INDR_VIEW.decl_date');
      $builder->orderBy('importance', 'desc');
      $builder->whereBetween('GLOB_ECO_INDR_VIEW.decl_date', [Carbon::now(), Carbon::now()->addDays(3)]);
      $builder->limit(1);
    });
  }

  public function getAreas()
  {

    $builder = Area::whereIn('area_uni_code', function ($query) {
      /** @var Builder $query */
      $query->select(['contry_area'])->from('GLOB_ECO_INDR_VIEW');
    });

    return $builder->get([
      "area_uni_code",
      "area_chi_name",
      "area_eng_name",
    ]);
  }

  public function events()
  {
    $domain = url('/');

    /** @var Builder $builder */
    $builder = Event::select([
      'decl_date as publish_at',
      'country_name', 'prom_place',
      'impot_level as importance',
      'event_conts as content',
      \DB::raw("CONCAT('$domain/flags/',PUB_AREA_CODE.area_chi_name,'.png') as flag"),
    ]);

    $builder->whereBetween('decl_date', [Carbon::today(), Carbon::tomorrow()]);

    $builder->leftJoin('PUB_AREA_CODE', 'PUB_AREA_CODE.area_chi_name', '=', 'country_name');
    if (\Request::get('web')) {
      $url = url()->current();
      $query = \Request::query();
      $query = http_build_query(array_except($query, 'page'));
      $perPage = \Request::get('per_page', 20);
      return $builder->paginate($perPage)->setPath($url . '?' . $query);
    }

    $builder->orderBy('decl_date', 'desc');
    $this->morphPage($builder);

    return $builder->get()->toArray();
  }
}