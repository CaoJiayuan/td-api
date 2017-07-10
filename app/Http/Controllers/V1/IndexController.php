<?php
/**
 * IndexController.php
 * Date: 2016/10/12
 * Time: 下午4:37
 * Created by Caojiayuan
 */

namespace App\Http\Controllers\V1;


use App\Http\Controllers\Controller;
use App\Repositories\IndexRepository;

class IndexController extends Controller
{
  public function index(IndexRepository $repository)
  {
    return $repository->index();
  }

  public function newIndex(IndexRepository $repository)
  {
    return $repository->newIndex($this->request->get('resolution'));
  }
}