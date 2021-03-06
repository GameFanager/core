<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

    $router->get('coming-soon', ['as' => 'coming-soon', function () {
        return view('livecms::coming-soon');
    }]);

    $router->get('redirect', ['as' => 'redirect', function () {
        return redirect()->to(request()->get('to'));
    }]);

    // PROFILE AREA

    $userSlug = getSlug('userhome');
    $router->group(['prefix' => $userSlug, 'namespace' => 'User', 'middleware' => 'auth'], function ($router) {

        $router->get('/', ['as' => 'user.home', function () {
            $bodyClass        = 'skin-blue sidebar-mini sidebar-collapse';

            return view('livecms::user', compact('bodyClass'));
        }]);

        $router->resource('profile', 'ProfileController');
    
    });

    // ADMIN AREA
    $router->group(['prefix' => $adminSlug, 'namespace' => 'Backend', 'middleware' => 'auth'], function ($router) {
        
        $router->get('/', ['as' => 'admin.home', function () {
            return view('livecms::admin.home');
        }]);

        $router->resource('permalink', 'PermalinkController');
        $router->resource('setting', 'SettingController');
        $router->resource('user', 'UserController');
        $router->resource('site', 'SiteController');

    });

    // AUTH
    $router->auth();

    $router->get('register', function () {
        return redirect()->route('user.home');
    });
