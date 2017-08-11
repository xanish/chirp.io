<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');
Route::get('/{username}', 'ProfileController@index');
Route::get('/followers', 'FollowsController@followers');
Route::get('/following', 'FollowsController@following');
Route::post('/follow/user/{username}', 'FollowsController@create');
Route::delete('/unfollow/user/{username}', 'FollowsController@destroy');
Route::get('/{username}', 'ProfileController@index');
Route::get('/{username}/followers', 'FollowsController@followersForUser');
Route::get('/{username}/following', 'FollowsController@followingForUser');
Route::get('/{username}/tweet', 'TweetController@create');
