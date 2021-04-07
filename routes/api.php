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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// auth route
Route::group([
    'middleware' => 'api',

], function ($router) {
    Route::post('/login', 'Auth\AuthController@login')->name('login');
    Route::post('/logout', 'Auth\AuthController@logout')->name('logout');
    Route::post('/refresh', 'Auth\AuthController@refresh')->name('refresh');
    Route::get('/user-profile', 'Auth\AuthController@userProfile')->name('profile');
       // Post route
	Route::ApiResource('post', 'Post\PostController', ['except' => ['update', 'update', 'destroy']]);
	Route::put('user/{user}/post/{post}', 'Post\PostController@update')->name('post.update'); 
	Route::post('user/{user}/post', 'Post\PostController@store')->name('post.create');
	Route::delete('user/{user}/post/{post}', 'Post\PostController@destroy')->name('post.delete');
	// Route::ApiResource('user.post', 'Post\UserPostController');
	// user route
	Route::ApiResource('user', 'User\UserController');
	Route::get('user/verify/{token}', 'User\UserController@verify')->name('verify');
	Route::get('user/{user}/resend', 'User\UserController@resend')->name('verifyResend');
});