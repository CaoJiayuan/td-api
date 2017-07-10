<?php

namespace App\Http\Middleware;

use Closure;

class AccessAllow
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request $request
   * @param  \Closure $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    /** @var \Illuminate\Http\Response $response */
    $response = $next($request);


    $response->header('Access-Control-Allow-Origin', '*');
    $response->header('Access-Control-Allow-METHOD', 'POST, GET, OPTIONS');
    $response->header('Access-Control-Allow-Headers', '*,x-csrf-token');
    return $response;
  }
}
