<?php

namespace App\Http\Controllers\PC;

use App\Repository\Repository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Traits\CurlTrait;
use App\Repositories\CalendarRepository;
use Carbon\Carbon;


class CalendarsController extends Controller
{
  public function index()
  {
    return view('pc.calendars.index');
  }

  //经济数据
  public function calendars(Request $request, CalendarRepository $repository)
  {
    $this->validateRequest([
      'date' => 'required|date'
    ]);
    $date = strtotime($request->input('date'));
    $data = $repository->getList($date, null, null, 1);
    return view('pc.calendars.calendars', ['data' => $data]);
  }

  //财经大事
  public function events(CalendarRepository $repository)
  {
    $data = $repository->events();
    return view('pc.calendars.events', ['data' => $data]);
  }

  //经济数据详情 indr area
  public function calendarsDetail(CalendarRepository $repository)
  {
    $now = strtotime(Carbon::now());
    $data = $repository->getList($now);
    $count = count($data);
    $date['title'] = $data[0]['title'];
    $date['interpretation'] = $data[0]['interpretation'];
    $date['attention'] = $data[0]['attention'];
    $date['frequency'] = $data[0]['frequency'];
    $date['stst_method'] = $data[0]['stst_method'];
    $date['mechanism'] = $data[0]['mechanism'];
    $date['effect'] = $data[0]['effect'];
    $date['next_time'] = $data[0]['next_time'];
    $date['data'] = '';
    $date['time'] = '';
    for ($i = 0; $i < $count; $i++) {
      if (!empty($data[$i]['value_before'])) {
        $date['data'] = $date['data'] . ',' . $data[$i]['value_before'];
        $date['time'] = $date['time'] . ',' . date('Y-m-d', $data[$i]['publish_at']);
      }
    }
    return view('pc.calendars.calendarsDetail', ['data' => $date]);
  }
}
