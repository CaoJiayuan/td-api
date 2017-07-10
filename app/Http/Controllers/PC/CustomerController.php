<?php

namespace App\Http\Controllers\PC;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\ServiceRepository;

class CustomerController extends Controller
{
  public function index(Request $request)
  {
    $id = $request->input('user_id');
    $device_id = $request->input('mid');
    return view('pc.index', ['id'=>$id,'device_id'=>$device_id]);
  }
}
