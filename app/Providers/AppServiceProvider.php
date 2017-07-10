<?php

namespace App\Providers;

use Dingo\Api\Exception\ValidationHttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    app('Dingo\Api\Exception\Handler')->register(function (ValidationHttpException $exception) {
      $code = 422;
      $errors = $exception->getErrors();
      $message = $errors->first();
      $res = ['code' => $code, 'errors' => $errors, 'message' => $message];
      return response()->json($res, $code);
    });
    app('Dingo\Api\Exception\Handler')->register(function (AuthenticationException $exception) {
      return response()->json(['code' => 401,'errors' => [], 'message' => '用户未登录,或登录状态已过期'], 401);
    });

    app('Dingo\Api\Exception\Handler')->register(function (UnauthorizedHttpException $exception) {
      return response()->json(['code' => 401,'errors' => [], 'message' => '用户未登录,或登录状态已过期'], 401);
    });

    \Validator::extend('mobile', function ($attribute, $value, $parameters) {
      if ($value == '') {
        return true;
      }
      return preg_match("/^1[0-9]{2}[0-9]{8}$|15[0189]{1}[0-9]{8}$|189[0-9]{8}$/", $value);
    }, '请输入正确的手机号');
  }

  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {

  }
}
