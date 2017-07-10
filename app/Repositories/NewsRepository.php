<?php
/**
 * NewsRepository.php
 * Date: 2016/10/12
 * Time: 下午2:44
 * Created by Caojiayuan
 */

namespace App\Repositories;


use Api\StarterKit\Utils\ApiResponse;
use App\Entity\Comment;
use App\Entity\FlashNews;
use App\Entity\News;
use App\Entity\NewsLike;
use App\Traits\Authenticated;
use App\Traits\ModelHelper;
use App\Traits\PageAble;
use App\Utils\ProtossApi;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class NewsRepository
{
  use PageAble, ModelHelper, ApiResponse, Authenticated;

  public function __construct()
  {
    \DB::setTablePrefix('');
  }

  public function getList($web = null, \Closure $after = null)
  {
    $pre = \DB::getTablePrefix();
    $domain = url('/' . env('API_PREFIX', ''));
    $builder = News::orderBy('pub_time', 'desc')
      ->select([
        'CMD_NEWS_MAIN.news_id as id',
        'title_main as title',
        'news_author as author',
        'news_sum as summary',
        'pub_time as published_at',
        'read_count AS read',
        'like_count AS like',
        "comment_count AS comments",
        \DB::raw("CONCAT('$domain/news/',CMD_NEWS_MAIN.news_id,'/html') as url"),
        \DB::raw("CASE WHEN {$pre}news_likes.id IS NULL THEN false ELSE true END AS liked"),
      ]);
    $builder->leftJoin('news_likes', function ($join) {
      $join->on('news_likes.user_id', '=', \DB::raw($this->getUserId(false)));
      $join->on('news_likes.news_id', '=', 'CMD_NEWS_MAIN.news_id');
    });

    if ($filter = \Request::get('filter')) {
      $this->likeOr($builder, [
        'title_main',
        'news_author',
        'news_sum',
        'html_txt',
      ], $filter);
    }

    $this->morphPage($builder);

    if ($after !== null) {
      $after($builder);
    }

    if ($web) {
      $perPage = \Request::get('per_page', 20);
      $url = url()->current();

      $query = \Request::query();
      $query = http_build_query(array_except($query, 'page'));
      return $builder->paginate($perPage)->setPath($url . '?' . $query);
    }

    return $builder->get()->toArray();
  }

  public function transNewsData($data)
  {

  }

  public function detail($id)
  {
    $pre = \DB::getTablePrefix();
    $domain = url('/' . env('API_PREFIX', ''));
    $builder = News::where('CMD_NEWS_MAIN.news_id', '=', $id)->select([
      'CMD_NEWS_MAIN.news_id as id',
      'title_main as title',
      'news_author as author',
      'news_sum as summary',
      'html_txt as content',
      'pub_time as published_at',
      'read_count AS read', 'like_count AS like',
      "comment_count AS comments",
      \DB::raw("CONCAT('$domain/news/',CMD_NEWS_MAIN.news_id,'/html') as url"),
      \DB::raw("CASE WHEN {$pre}news_likes.id IS NULL THEN false ELSE true END AS liked"),
    ]);

    $builder->leftJoin('news_likes', function ($join) {
      $join->on('news_likes.user_id', '=', \DB::raw($this->getUserId(false)));
      $join->on('news_likes.news_id', '=', 'CMD_NEWS_MAIN.news_id');
    });


    $builder->increment('read_count');
    $news = $builder->first();
    if (!$news) {
      return $this->respondNotFound('该资讯不存在');
    }

    return $news;
  }

  public function like($id)
  {
    $userId = $this->getUserId();

    $like = \DB::transaction(function () use ($id, $userId) {
      if (NewsLike::where([
        'user_id' => $userId,
        'news_id' => $id,
      ])->delete()
      ) {
        NewsLike::where([
          'user_id' => $userId,
          'news_id' => $id,
        ])->delete();
        News::where('news_id', $id)->decrement('like_count');
        return false;
      } else {
        NewsLike::create([
          'user_id' => $userId,
          'news_id' => $id,
        ]);
        News::where('news_id', $id)->increment('like_count');
        return true;
      }
    });
    if ($like) {
      return $this->respondSuccess('点赞成功');
    } else {
      return $this->respondSuccess('取消点赞成功');
    }
  }


  public function getFlashes($web = null, \Closure $after = null)
  {
    /** @var Builder $builder */
    $builder = FlashNews::orderBy('pub_time', 'desc')
      ->select([
        'flash_id as id',
        \DB::raw("REPLACE(flash_sum,'Jin10.com','') as summary"),
        'pub_time as published_at',
      ]);

    $this->morphPage($builder);

    if ($after !== null) {
      $after($builder);
    }

    if ($web) {
      $perPage = \Request::get('per_page', 20);
      $url = url()->current();

      $query = \Request::query();
      $query = http_build_query(array_except($query, 'page'));
      /** @var LengthAwarePaginator $paginate */
      $paginate = $builder->paginate($perPage)->setPath($url . '?' . $query);
      $result = $paginate->toArray();
      $result['data'] = $this->transformFlashes($result['data']);
      return $result;
    }

    return $this->transformFlashes($builder->get()->toArray());
  }

  protected function transformFlashes($flashes)
  {
    $result = [];
    foreach ($flashes as $flash) {
      $result[] = $this->transformFlashItem($flash);
    }

    return $result;
  }

  protected function transformFlashItem($flash)
  {
    $flash['summary'] = preg_replace('#<.*?>#', '', $flash['summary']);
    return $flash;
  }

  public function flashDetail($id)
  {
    return FlashNews::where('flash_id', '=', $id)->select([
      'flash_id as id',
      'flash_sum as summary',
      'pub_time as published_at',
    ])->first();
  }

  public function exclusives()
  {
    $data = \Request::all();
    $data['prefix'] = url(env('API_PREFIX', '') . '/exclusives');
    return ProtossApi::get('news', $data);
  }

  public function readExclusive($id)
  {
    return ProtossApi::get('news/' . $id);
  }

  public function postComment($id, $data)
  {

    \DB::transaction(function () use ($id, $data) {
      $data['news_id'] = $id;
      News::where('news_id', $id)->increment('comment_count');
      return Comment::create($data)
        ->toArray();
    });
  }

  public function postExComment($id, $data)
  {
    $data['news_id'] = $id;
    Comment::$con = 'protoss';
    return Comment::create($data)
      ->toArray();
  }

  public function comments($newsId, $exclusive = false)
  {
    $con = $exclusive ? 'protoss' : 'mysql';
    Comment::$con = $con;
    $c = new Comment();
    $pre = $c->getConnection()->getTablePrefix();
    $builder = $c->where('comments.news_id', $newsId)
      ->where('comments.status', 1)
      ->orderBy('created_at', 'desc')
      ->with(['parent' => function ($builder) {
        $builder->where('status', 1)->select([
          'comments.id', 'comments.content', 'comments.created_at', 'comments.user_id'
        ]);
      }, 'user'        => function ($builder) {
        $builder->select([
          'id',
          'phone',
          'nick_name',
          'avatar',
        ]);
      }, 'parent.user' => function ($builder) {
        $builder->select([
          'id',
          'phone',
          'nick_name',
          'avatar',
        ]);
      }])
      ->leftJoin('comments as pc', 'pc.parent_id', '=', 'comments.id')
      ->groupBy('comments.id')
      ->select([
        'comments.id', 'comments.content', 'comments.created_at', 'comments.user_id', 'comments.parent_id'
      ]);
    return $this->getChangeAblePage($builder);
  }

  public function childrenComments($id, $exclusive = false)
  {
    $con = $exclusive ? 'protoss' : 'mysql';
    Comment::$con = $con;
    $builder = Comment::whereParentId($id)->where('comments.status', 1)
      ->with(['parent' => function ($builder) {
        $builder->select([
          'comments.id', 'comments.content', 'comments.created_at', 'comments.user_id'
        ]);
      }, 'user'        => function ($builder) {
        $builder->select([
          'id',
          'phone',
          'nick_name',
          'avatar',
        ]);
      }, 'parent.user' => function ($builder) {
        $builder->select([
          'id',
          'phone',
          'nick_name',
          'avatar',
        ]);
      }])->orderBy('id', 'desc')
      ->select([
        'comments.id', 'comments.content', 'comments.created_at', 'comments.user_id', 'comments.parent_id'
      ]);
    return $this->getChangeAblePage($builder);
  }

  public function exComments($id)
  {
    return ProtossApi::get('/news/' . $id . '/comments', \Request::all());
  }
}