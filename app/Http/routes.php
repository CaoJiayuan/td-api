<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
use Dingo\Api\Routing\Router;

/** @var Router $api */
$api = app('api.router');


$api->version('v1', ['namespace' => 'App\Http\Controllers\V1', 'middleware' => ['api.access']], function (Router $api) {
  $api->get('/', function (\App\Repositories\ServiceRepository $api) {
    return 'TD';
  });

  $api->group(['middleware' => 'session'], function (Router $api) {
    $api->any('OAuth/{type}', 'OAuthController@login')->middleware('oauth.state');
    $api->any('OAuth/{type}/go', 'OAuthController@page');
  });

  $api->get('/page/share', function () {
    return view('share');
  });

  $api->get('/new/index', 'IndexController@newIndex');
  $api->get('/version', 'InfoController@update');

  $api->get('product', 'ProductsController@index');//
  $api->get('resource/{id}/html', 'InfoController@resource');//
  $api->get('share', 'InfoController@share');//
  $api->post('feedback', 'InfoController@feedback');//
  $api->post('news/comments/{id}/complaint', 'InfoController@complaint');//
  $api->post('exclusives/comments/{id}/complaint', 'InfoController@complaintEx');//
  $api->get('feedback', 'InfoController@myFeedback');//
  $api->get('ads/rand', 'InfoController@randomAd');//
  $api->get('ads/float', 'InfoController@getFloatAd');//
  $api->get('news', 'NewsController@index');//
  $api->get('news/{id}/comments', 'NewsController@comments');//
  $api->get('news/comments/{id}/children', 'NewsController@cComments');//
  $api->post('news/{id}/comment', 'NewsController@postComment');//
  $api->get('exclusives', 'NewsController@exclusives');//
  $api->get('exclusives/{id}', 'NewsController@readExclusive');//
  $api->get('exclusives/{id}/share', 'ExclusiveController@shareData');//
  $api->get('exclusives/{id}/comments', 'NewsController@exComments');//
  $api->get('exclusives/comments/{id}/children', 'NewsController@cExComments');//
  $api->get('exclusives/{id}/html', 'ExclusiveController@html');//
  $api->post('exclusives/{id}/like', 'ExclusiveController@like');//
  $api->post('exclusives/{id}/comment', 'NewsController@postExComment');//
  $api->options('news', function () {
    return '';
  });
  $api->get('news/flashes', 'NewsController@flashes');//
  $api->options('news/flashes', function () {
    return '';
  });
  $api->get('news/flashes/{id}', 'NewsController@flash');//
  $api->options('news/flashes/{id}', function () {
    return '';
  });


  $api->get('news/{id}', 'NewsController@detail');//
  $api->post('news/{id}/like', 'NewsController@like');//
  $api->get('news/{id}/html', 'NewsController@html');//
  $api->options('news/{id}', function () {
    return '';
  });

  $api->get('index', 'IndexController@index');//
  $api->get('calendars', 'CalendarController@index');//
  $api->options('calendars', function () {
    return '';
  });

  $api->get('calendars/coming', 'CalendarController@coming');//
  $api->options('calendars', function () {
    return '';
  });

  $api->get('events', 'CalendarController@events');//
  $api->options('events', function () {
    return '';
  });

  $api->get('calendars/areas', 'CalendarController@areas');//

  $api->get('licenses/{view}', 'InfoController@licenses');//
  $api->get('page/test', 'InfoController@test');//

  $api->group(['prefix' => 'service'], function (Router $api) {
    $api->post('/login', 'ServiceController@login');
    $api->post('/login/chatter', 'ServiceController@loginChatter');
    $api->get('/chatter/{id}/match', 'ServiceController@matchService');
    $api->post('/chatter', 'ServiceController@editImUser');
    $api->post('/chatter/assign', 'ServiceController@assignImUsers');
    $api->group(['middleware' => 'api.auth'], function (Router $api) {
      $api->post('/send/{type?}', 'ServiceController@send')->middleware('throttle:30,1');
      $api->get('/contacts', 'ServiceController@contacts');
      $api->get('/disconnect', 'ServiceController@disconnect');
      $api->get('/user', 'ServiceController@getAccount');
      $api->get('/messages/group', 'ServiceController@groupMessages');
      $api->post('/messages/group/{id}/delete', 'ServiceController@deleteGroupMessage');
      $api->post('/chatters', 'ServiceController@getImUser');
      $api->post('/user', 'ServiceController@editAccount');
      $api->post('/logout', 'ServiceController@logout');
      $api->get('/conversions', 'ServiceController@conversions');
    });
  });

  $api->get('/about', 'InfoController@about');
  $api->get('/about/article', 'InfoController@aboutItem');
});

//PC端网页
Route::group(['prefix'=>'pc','namespace'=>'PC'],function(){
  Route::get('news', 'NewsController@index');
  Route::get('article', 'NewsController@article');
  Route::get('flash', 'NewsController@flash');
  Route::get('announcement', 'NewsController@announcement');
  Route::get('announcement/detail', 'NewsController@detail');

  Route::get('information', 'CalendarsController@index');
  Route::get('calendars', 'CalendarsController@calendars');
  Route::get('calendarsDetail', 'CalendarsController@calendarsDetail');
  Route::get('events', 'CalendarsController@events');
  Route::get('smallNews', 'SmallController@index');
  Route::get('smallCalendars', 'SmallController@calendars');
  Route::get('protocol', function () {
    return view('pc.protocol');
  });
  Route::get('customer','CustomerController@index');
});
