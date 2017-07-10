<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Repositories\ExclusiveRepository;
use App\Repositories\InfoRepository;
use App\Repositories\NewsRepository;
use Illuminate\Http\Request;

/**
 * NewsController.php
 * Date: 2016/10/12
 * Time: 下午2:44
 * Created by Caojiayuan
 */
class NewsController extends Controller
{
  public function index(NewsRepository $repository)
  {
    $web = $this->inputGet('web');
    return $repository->getList($web);
  }

  public function flashes(NewsRepository $repository)
  {
    $web = $this->inputGet('web');
    return $repository->getFlashes($web);
  }


  public function detail(NewsRepository $repository, $id)
  {
    return $this->respondWithItem($repository->detail($id));
  }

  public function like(NewsRepository $repository, Request $request, $id)
  {
    return $repository->like($id);
  }

  public function html(NewsRepository $repository, InfoRepository $infoRepository, Request $request, $id)
  {
    $detail = $repository->detail($id);
    $detail['ads'] = $infoRepository->randomAds(7, 8);
    $detail['comments'] = null;
    if (!str_contains($request->header('User-Agent'), 'jsjapp')) {
      \Request::offsetSet('web', 1);
      $detail['comments'] = $repository->comments($id);
    }

    return view('news', $detail);
  }

  public function flash(NewsRepository $repository, $id)
  {
    return $this->respondWithItem($repository->flashDetail($id));
  }

  public function exclusives(ExclusiveRepository $repository)
  {
    return $repository->exclusives();
  }

  public function readExclusive(ExclusiveRepository $repository, $id)
  {
    return $repository->detail($id);
  }

  public function comments(NewsRepository $repository, $id)
  {
    return $repository->comments($id);
  }

  public function cComments(NewsRepository $repository, $id)
  {
    return $repository->childrenComments($id);
  }

  public function cExComments(NewsRepository $repository, $id)
  {
    return $repository->childrenComments($id, true);
  }

  public function exComments(NewsRepository $repository, $id)
  {
    return $repository->comments($id, true);
  }

  public function postComment(NewsRepository $repository, $id)
  {
    $data = $this->getValidatedData([
      'user_id' => 'required',
      'content' => 'required',
      'parent_id',
    ]);
    $repository->postComment($id, $data);

    return $this->respondSuccess('评论成功');
  }

  public function postExComment(NewsRepository $repository, $id)
  {
    $data = $this->getValidatedData([
      'user_id' => 'required',
      'content' => 'required',
      'parent_id',
    ]);

    $repository->postExComment($id, $data);
    return $this->respondSuccess('评论成功');
  }
}