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

// Route::get('/{username}', 'ProfileController');
//
// Route::get('/followers', 'FollowsController@followers');
//
// Route::get('/following', 'FollowsController@following');
//
// Route::post('/chirp', 'ChirpController@index');

Auth::routes();

Route::get('/home', 'HomeController@index');
Route::get('/followers', 'FollowsController@followers');
Route::get('/following', 'FollowsController@following');
Route::post('/follower/{follower}', 'FollowsController@create');
Route::delete('/follower/{follower}', 'FollowsController@destroy');
Route::get('/{username}', 'ProfileController@index');
Route::get('/{username}/followers', 'FollowsController@followersForUser');
Route::get('/{username}/following', 'FollowsController@followingForUser');
