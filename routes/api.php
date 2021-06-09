<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
    ], function ($router) {
        Route::post('login', 'AuthController@login');
        Route::post('logout', 'AuthController@logout');
        Route::post('refresh', 'AuthController@refresh');
        Route::post('me', 'AuthController@me');
        Route::post('register', 'AuthController@register');
});

// post route
Route::group(['prefix' => 'post', 'middleware' => ['api']], function () {
    Route::get('index', 'PostController@index');
    Route::post('store', 'PostController@store');
    Route::get('edit/{id}', 'PostController@edit');
    Route::post('update/{id}', 'PostController@update');
    Route::post('destroy/{id}', 'PostController@destroy');
});

// post category
Route::group(['prefix' => 'category', 'middleware' => ['api']], function () {
    Route::get('index', 'CategoryController@index');
    Route::post('store', 'CategoryController@store');
    Route::post('update/{id}', 'CategoryController@update');
    Route::post('destroy/{id}', 'CategoryController@destroy');
});

// setting
Route::group(['prefix' => 'setting', 'middleware' => ['api']], function () {
    Route::get('index', 'SettingController@index');
    Route::post('store', 'SettingController@store');
});


Route::get('tranding-list', 'FrontController@trandingList');
Route::get('social-media', 'FrontController@socialMedia');
Route::get('categories', 'FrontController@categories');
Route::post('post/list', 'FrontController@postList');
Route::post('post/byCategory', 'FrontController@postByCategory');