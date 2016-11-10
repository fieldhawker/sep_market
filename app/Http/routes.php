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

// 記述例
//Route::get('/', function () {
//    return view('welcome');
//});

//Route::group(['middleware' => 'web'], function () {

    // multi auth なのでデフォルトは使わない
    //Route::auth();

//////////////////////////////////////////////////////////////////////

    Route::group(['prefix' => 'users'], function () {

        // 一般ログイン設定

        // Authentication Routes...
        $this->get('/login', 'UsersAuth\AuthController@showLoginForm');
        $this->post('/login', 'UsersAuth\AuthController@login');
        $this->get('/logout', 'UsersAuth\AuthController@logout');

        // Registration Routes...
        $this->get('/register', 'UsersAuth\AuthController@showRegistrationForm');
        $this->post('/register', 'UsersAuth\AuthController@register');

        // Password Reset Routes...
        $this->get('/password/reset/{token?}', 'UsersAuth\PasswordController@showResetForm');
        $this->post('/password/email', 'UsersAuth\PasswordController@sendResetLinkEmail');
        $this->post('/password/reset', 'UsersAuth\PasswordController@reset');

//        Route::get('/', 'UsersHomeController@index');
//        Route::get('/home', 'UsersHomeController@index');
        Route::get('/', 'HomeController@index');
        Route::get('/home', 'HomeController@index');
    });

//////////////////////////////////////////////////////////////////////

    Route::group(['prefix' => 'admin'], function () {

        // 管理者ログイン設定

        // Authentication Routes...
        $this->get('/login', 'AdminAuth\AuthController@showLoginForm');
        $this->post('/login', 'AdminAuth\AuthController@login');
        $this->get('/logout', 'AdminAuth\AuthController@logout');

        // Registration Routes...
        $this->get('/register', 'AdminAuth\AuthController@showRegistrationForm');
        $this->post('/register', 'AdminAuth\AuthController@register');

        // Password Reset Routes...
        $this->get('/password/reset/{token?}', 'AdminAuth\PasswordController@showResetForm');
        $this->post('/password/email', 'AdminAuth\PasswordController@sendResetLinkEmail');
        $this->post('/password/reset', 'AdminAuth\PasswordController@reset');

        // Home
        Route::get('/', 'Admin\HomeController@index');
        Route::get('/home', 'Admin\HomeController@index');

        Route::resource('/users', 'Admin\UsersController');

        Route::resource('/ope', 'Admin\OperationLogsController');

        Route::resource('/exc', 'Admin\ExclusivesController');
        Route::resource('/items', 'Admin\ItemsController');

    });

//////////////////////////////////////////////////////////////////////

    // API

    Route::group(['prefix' => 'api'], function () {
        
        Route::resource('users', 'Api\UsersController');

    });

    Route::get('/', 'HomeController@index');
    Route::get('/home', 'HomeController@index');

//});

