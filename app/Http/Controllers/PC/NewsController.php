<?php

namespace App\Http\Controllers\PC;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Traits\CurlTrait;
use Carbon\Carbon;

class NewsController extends Controller
{
  use CurlTrait;

  public function index()
  {
    return view('pc.news.news');
  }

  public function flash()
  {
    return view('pc.news.flash');
  }

  public function announcement()
  {
    $map['startTime'] = Carbon::today();
    $nap['endTime'] = Carbon::tomorrow();
    $map['goldNum'] = '1080184574';
    $url = config('interface.java').'query/noticeQuery';
    $data = $this->getData($url,'post',$map);
    if(isset($data['code']))
    {
      return view('pc.errors.500');
    }
    return view('pc.news.announcement',['data'=>$data]);
  }

  public function detail(Request $request)
  {
    $this->validate($request,[
      'id' => 'required'
    ]);

    $map['bulletinId'] = $request->input('id');
    $map['goldNum'] = '1080184574';
    $url = config('interface.java').'query/noticeQuery';
    $data = $this->getData($url,'post',$map);
    return json_encode($data);
  }
}
