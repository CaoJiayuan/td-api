<?php
/**
 * StudioController.php
 * Date: 2016/10/12
 * Time: 下午3:08
 * Created by Caojiayuan
 */

namespace App\Http\Controllers\V1;


use App\Http\Controllers\Controller;
use App\Repositories\StudioRepository;

class StudioController extends Controller
{
  public function index(StudioRepository $repository)
  {
    return $this->respondWithCollection($repository->getList());
  }
}