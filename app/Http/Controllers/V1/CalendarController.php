<?php
/**
 * IndexController.php
 * Date: 2016/10/12
 * Time: 下午4:37
 * Created by Caojiayuan
 */

namespace App\Http\Controllers\V1;


use App\Http\Controllers\Controller;
use App\Repositories\CalendarRepository;

class CalendarController extends Controller
{
  public function index(CalendarRepository $repository)
  {
    $this->validateRequest([
      'date' => 'date'
    ]);
    $date = $this->inputGet('date');
    $indr = $this->inputGet('indr');
    $area = $this->inputGet('area');
    $web = $this->inputGet('web');
    if (!$date) {
      $time = time();
    } else {
      $time = strtotime($date);
    }

    return $repository->getList($time, $indr, $area, $web);
  }

  public function areas(CalendarRepository $repository)
  {
    return $this->respondWithCollection($repository->getAreas());
  }

  public function events(CalendarRepository $repository)
  {
    return $repository->events();
  }

  public function coming(CalendarRepository $repository)
  {
    return $repository->coming();
  }
}