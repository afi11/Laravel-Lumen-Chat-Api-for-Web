<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

Route::options('/{any:.*}',['middleware' => ['CorsMiddleware'], function (){ 
            return response(['status' => 'success']); 
        }
    ]
);

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->group(['prefix' => 'api','middleware' => 'CorsMiddleware'], function() use($router){

    $router->post('testapi','ChatController@testapi');

    // Auth
    $router->post('register','UserController@register');
    $router->post('login','UserController@login');
    $router->post('tescrud','TesController@testing');
    $router->get('getuser/{id}','UserController@getUserById');
    $router->get('updateuser/{id}','UserController@updateUser');
    $router->get('updateuserclose/{id}','UserController@upUserClose');
    $router->post('update_profil','UserController@update');
    $router->post('verify_user','UserController@verifiyUser');
    $router->post('cekusertoreset','UserController@sendEmailToResetPass');
    $router->post('reset_password','UserController@resetPassword');

    // Home
    $router->get('home','HomeController@index');
    $router->get('profil','HomeController@getprofil');
    $router->get('letmessage','HomeController@letMessageWithPeople');
    $router->get('latestmessage','HomeController@latestMessage');
    $router->get('countunreadmessage/{sender}/{receiver}','HomeController@countUnreadMessage');
    $router->get('unreadmessage/{sender}','HomeController@unReadMessage');
    $router->get('getlatetsmessage/{sender}/{receiver}','HomeController@getLatestMessage');

    // Chat
    $router->get('getchat/{receiver}','ChatController@getChat');
    $router->post('sendchat','ChatController@sendMessage');
    $router->get('readmessage/{receiver}','ChatController@readMessage');
});

$router->get('/key',function(){
    return \Illuminate\Support\Str::random(32);
});