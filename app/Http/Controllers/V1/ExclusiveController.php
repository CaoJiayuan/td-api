<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-3-22
 * Time: 上午10:52
 */

namespace App\Http\Controllers\V1;


use App\Http\Controllers\Controller;
use App\Repositories\ExclusiveRepository;
use App\Repositories\InfoRepository;
use App\Repositories\NewsRepository;
use Illuminate\Http\Request;

class ExclusiveController extends Controller
{

  public function html(ExclusiveRepository $repository, InfoRepository $infoRepository, NewsRepository $newsRepository, Request $request, $id)
  {
    $news = $repository->detail($id);
    $news['ads'] = $infoRepository->randomAds(5, 6);
    $news['comments'] = null;
    if (!str_contains($request->header('User-Agent'), 'jsjapp')) {
      \Request::offsetSet('web', 1);
      $news['comments'] = $newsRepository->comments($id, true);
    }
    return view('exclusive', $news);
  }

  public function like(ExclusiveRepository $repository, $id)
  {
    return $repository->like($id);
  }

  public function comment(ExclusiveRepository $repository, $id)
  {

  }

  public function shareData(ExclusiveRepository $repository, $id)
  {
    return $this->respondWithItem($repository->shareData($id));
  }
}