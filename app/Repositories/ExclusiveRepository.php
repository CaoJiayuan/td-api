<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-3-22
 * Time: 上午10:34
 */

namespace App\Repositories;


use Api\StarterKit\Utils\ApiResponse;
use App\Entity\Complaint;
use App\Entity\Exclusive;
use App\Entity\ExclusiveLike;
use App\Traits\Authenticated;
use App\Traits\PageAble;
use Carbon\Carbon;

class ExclusiveRepository
{
  use Authenticated, PageAble, ApiResponse;

  public function exclusives(\Closure $callback = null, $urlPrefix = null)
  {
    $builder = Exclusive::where('published_at', '<', Carbon::now());

    $builder->leftJoin('news_categories', 'news_categories.id', '=', 'news_category_id');
    $builder->leftJoin('comments', 'comments.news_id', '=', 'news.id');
    $builder->leftJoin('news_likes', function ($join) {
      $join->on('news_likes.user_id', '=', \DB::raw($this->getUserId(false)));
      $join->on('news_likes.news_id', '=', 'news.id');
    });
    if ($callback) {
      $callback($builder);
    }
    $builder->orderBy('published_at', 'desc');
    $urlPrefix = $urlPrefix ?: url(env('API_PREFIX', '') . '/exclusives');
    $pre = $builder->getConnection()->getTablePrefix();
    $builder->select([
      'news.id',
      'news.title',
      'news.from',
      'news.summary',
      'news.thumbnail',
      'news.content',
      'news.published_at',
      'news.read',
      'news_categories.name as category',
      'news.like',
      \DB::raw("CASE WHEN {$pre}news_likes.id IS NULL THEN false ELSE true END AS liked"),
      \DB::raw("COUNT({$pre}comments.id) AS comments"),
      \DB::raw("CONCAT('$urlPrefix/',{$pre}news.id,'/html') as url"),
    ])->groupBy('news.id');

    if (\Request::get('web')) {
      $perPage = \Request::get('per_page', 20);
      $url = url()->current();

      $query = \Request::query();
      $query = http_build_query(array_except($query, 'page'));
      return $builder->paginate($perPage)->setPath($url . '?' . $query);
    }
    $this->morphPage($builder);

    return $builder->get()->toArray();
  }


  public function detail($id)
  {
    $builder = Exclusive::select([
      'news.id',
      'news.title',
      'news.from',
      'news.summary',
      'news.thumbnail',
      'news.content',
      'news.published_at',
      'news.read',
      'news_categories.name as category',
      'news.like',
    ])->leftJoin('news_categories', 'news_categories.id', '=', 'news_category_id');
    $builder->leftJoin('comments', 'comments.news_id', '=', 'news.id');
    $pre = $builder->getConnection()->getTablePrefix();
    $domain = url('/' . env('API_PREFIX', ''));
    $builder->addSelect([
      \DB::raw("COUNT({$pre}comments.id) AS comments"),
      \DB::raw("CONCAT('$domain/exclusives/',{$pre}news.id,'/html') as url"),
      \DB::raw("CASE WHEN {$pre}news_likes.id IS NULL THEN false ELSE true END AS liked"),
    ]);

    $builder->leftJoin('news_likes', function ($join) {
      $join->on('news_likes.user_id', '=', \DB::raw($this->getUserId(false)));
      $join->on('news_likes.news_id', '=', 'news.id');
    });
    if (!$news = $builder->find($id)) {
      return $this->respondNotFound('未找到该专栏');
    }
    $news->read++;
    $news->save();
    $news->addHidden('updated_at');
    return $news->toArray();
  }

  public function complaintC($data)
  {
    $data['user_id'] = $this->getUserId();
    Complaint::create($data);
  }

  public function like($id)
  {
    $userId = $this->getUserId();

    $like = \DB::transaction(function () use ($id, $userId) {
      if (ExclusiveLike::where([
        'user_id' => $userId,
        'news_id' => $id,
      ])->delete()) {
        ExclusiveLike::where([
          'user_id' => $userId,
          'news_id' => $id,
        ])->delete();
        Exclusive::where('id', $id)->decrement('like');
        return false;
      } else {
        ExclusiveLike::create([
          'user_id' => $userId,
          'news_id' => $id,
        ]);
        Exclusive::where('id', $id)->increment('like');
        return true;
      }
    });
    if ($like) {
      return $this->respondSuccess('点赞成功');
    } else {
      return $this->respondSuccess('取消点赞成功');
    }
  }

  public function comment()
  {

  }

  public function shareData($id)
  {
    $maxLength = 50;
    $ex = Exclusive::findOrFail($id, [
      'id','title',\DB::raw("LEFT(summary, $maxLength) AS summary")
    ]);

    return $ex;
  }
}