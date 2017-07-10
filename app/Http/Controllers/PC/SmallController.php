<?php

namespace App\Http\Controllers\PC;

use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Traits\CurlTrait;
use App\Repositories\CalendarRepository;
use App\Repositories\NewsRepository;

class SmallController extends Controller
{
  public function index(NewsRepository $repository)
  {
    $data = $repository->getFlashes();
    return view('pc.small.news', ['data' => $data]);
  }

  public function calendars(CalendarRepository $repository)
  {
    $msg['date'] = date('Y-m-d',time());
    $date = strtotime(Carbon::now());
    $data = $repository->getList($date);
    if(count($data)>3)
    {
      $data = array_slice($data,0,3);
    }
    return view('pc.small.calendars', ['data' => $data]);
  }
}
