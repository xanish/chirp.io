<?php

Route::get('/', 'SiteHomepageController@index');

Auth::routes();

Route::resource('/followers', 'FollowersController');
Route::resource('/following', 'FollowingsController');

Route::get('/home', 'HomeController@index');
Route::post('/tweet', 'TweetController@create');

Route::get('/edit-profile', 'EditProfileController@index');
Route::patch('/edit-profile', 'EditProfileController@update');

Route::get('/{username}', 'ProfileController@index');
