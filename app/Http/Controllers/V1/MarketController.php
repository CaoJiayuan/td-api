<?php
/**
 * StudioController.php
 * Date: 2016/10/12
 * Time: 下午3:08
 * Created by Caojiayuan
 */

namespace App\Http\Controllers\V1;


use App\Entity\Market;
use App\Entity\MarketCategory;
use App\Http\Controllers\Controller;

class MarketController extends Controller
{
  public function index()
  {
    return $this->respondWithCollection(Market::all());
  }


  public function category()
  {
    return $this->respondWithCollection(MarketCategory::with('markets')->get());
  }
  
}