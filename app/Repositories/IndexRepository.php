<?php
/**
 * NewsRepository.php
 * Date: 2016/10/12
 * Time: 下午2:44
 * Created by Caojiayuan
 */

namespace App\Repositories;


use Illuminate\Database\Query\Builder;

class IndexRepository
{
  public function index()
  {
    $data = [
      'calenders' => (new CalendarRepository())->coming(),
      'news'      => (new NewsRepository())->getList(null, function ($builder) {
        $builder->limit(config('consts.IndexNewsLimit'));
      }),
      'flashes'   => (new NewsRepository())->getFlashes(null, function ($builder) {
        $builder->limit(config('consts.IndexFlashesLimit'));
      }),
    ];

    return $data;
  }

  public function newIndex($resolution)
  {
    $ads = $this->getAds($resolution);

    $data['launcher'] = array_get($ads, 'launcher', null);
    $data['banners'] = array_get($ads, 'banners', []);
    $data['ads'] = array_get($ads, 'ads', []);
    $data['exclusives'] = array_get($ads, 'news', []);

    $data = array_merge($data, [
      'calenders' => (new CalendarRepository())->coming(),
      'news'      => (new NewsRepository())->getList(null, function ($builder) {
        /** @var Builder $builder */
        $builder->limit(config('consts.IndexNewsLimit'));
      }),
      'flashes'   => (new NewsRepository())->getFlashes(null, function ($builder) {
        $builder->limit(config('consts.IndexFlashesLimit'));
      }),
    ]);

    return $data;
  }

  public function getAds($resolution)
  {
    $infoRepository = new InfoRepository();
    $launcher = $infoRepository->ads(function ($builder) use ($resolution) {
      /** @var Builder $builder */
      $builder->whereBetween('ad_position_id',  [9, 20])
        ->where('size', $resolution)
        ->limit(1);
    });
    return [
      'launcher' => reset($launcher) ?: null,
      'banners' => $infoRepository->ads(function ($builder) {
        $builder->where('ad_position_id', '=', 1)
          ->orderBy('ads.id','desc');
      }),
      'ads'     => $infoRepository->orderThenGroup(2, 4, 'id desc'),
      'news'    => (new ExclusiveRepository)->exclusives(function ($builder) {
        $builder->limit(3);
      }),
    ];
  }
}