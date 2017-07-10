<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-3-13
 * Time: 下午3:32
 */

namespace App\Http\Controllers\V1;


use App\Entity\Agreement;
use App\Entity\Resources;
use App\Entity\Test;
use App\Entity\Version;
use App\Http\Controllers\Controller;
use App\Repositories\ExclusiveRepository;
use App\Repositories\InfoRepository;

class InfoController extends Controller
{
  public function licenses($name)
  {
    $data = Agreement::whereTitle($name)->first();
    if (!$data) {
      return $this->respondNotFound('页面不存在');
    }

    return view('licenses', $data);
  }

  public function test(Test $test)
  {
    return view('test', [
      'data' => $test->all()
    ]);
  }

  public function randomAd(InfoRepository $repository)
  {
    return $repository->randomAds(1, 8);
  }

  public function feedback(InfoRepository $repository)
  {
    $data = $this->getValidatedData([
      'mobile'  => 'required|mobile',
      'content' => 'required|max:255',
      'email'   => 'email|max:255',
      'img', 'user_id'
    ]);
    $repository->feedback($data);

    return $this->respondSuccess('反馈成功');
  }

  public function myFeedback(InfoRepository $repository)
  {
    return $repository->myFeedback();
  }

  public function complaint(ExclusiveRepository $repository, $id)
  {
    $data = $this->getValidatedData([
      'content' => 'required'
    ]);
    $data['comment_id'] = $id;
    $data['type'] = 0;
    $repository->complaintC($data);
    return $this->respondSuccess('投诉成功');
  }

  public function complaintEx(ExclusiveRepository $repository, $id)
  {
    $data = $this->getValidatedData([
      'content' => 'required'
    ]);
    $data['comment_id'] = $id;
    $data['type'] = 1;
    $repository->complaintC($data);
    return $this->respondSuccess('投诉成功');
  }

  public function share()
  {
    return [
      'url'   => url(env('API_PREFIX', '') . '/page/share'),
      'title' => env('SHARE_TITLE', '金生金'),
      'desc'  => env('SHARE_DESC', '我正在使用金生金,黄金白银投资更便捷!')
    ];
  }

  public function getFloatAd(InfoRepository $repository)
  {
    $ad = $repository->ads(function ($builder) {
      $builder->where('ad_position_id', 6);
      $builder->limit(1);
    });
    return reset($ad) ?: $this->respondNotFound();
  }

  public function aboutItem()
  {
    return view('about.article');
  }

  public function about()
  {
    return view('about.list');
  }

  public function resource($id)
  {
    $data = Resources::find($id);

    if (!$data) {
      return $this->respondNotFound('');
    }
    return view('resource', $data);
  }

  public function update()
  {
    $version = Version::orderBy('created_at','desc')->first([
      'type', 'version','build', 'content','created_at'
    ]);
    if (!$version) {
      return $this->respondNotFound('当前无版本更新');
    }
    return $this->respondWithItem($version);
  }
}