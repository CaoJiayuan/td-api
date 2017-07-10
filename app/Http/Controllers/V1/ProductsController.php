<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-3-3
 * Time: 下午3:08
 */

namespace App\Http\Controllers\V1;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
  public function index(Request $request)
  {
    $variety = $request->get('code');


    switch ($variety) {
      case 'Au' :
        return view('Au');
      case 'mAu' :
        return view('mAu');
      case 'Ag' :
        return view('Ag');
      default :
        return view('Au');
    }
  }
}