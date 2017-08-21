<?php

Route::get('/', 'SiteHomepageController@index');

Auth::routes();

Route::resource('/followers', 'FollowersController', [
  'only' => [
    'index', 'show',
  ],
]);
Route::resource('/following', 'FollowingsController', [
  'except' => [
    'create', 'destroy', 'edit',
  ],
]);

Route::get('/home', 'HomeController@index');
Route::post('/tweet', 'TweetController@create');

Route::get('/edit-profile', 'EditProfileController@index');
Route::patch('/edit-profile', 'EditProfileController@update');

Route::get('/ajaxfeed', 'ProfileController@ajaxfeed');

Route::post('/tweet', 'TweetController@create');
Route::get('/{username}', 'ProfileController@index');
