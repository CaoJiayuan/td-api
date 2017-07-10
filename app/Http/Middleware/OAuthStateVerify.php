<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpKernel\Exception\HttpException;

class OAuthStateVerify
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
    $state = $request->get('state');

    $sessionToken = $request->session()->token();

    if (! is_string($sessionToken) || ! is_string($state) || !hash_equals($sessionToken, $state)) {
      throw new HttpException(403, 'Access denied!');
    }

    return $next($request);
  }
}
