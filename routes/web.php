<?php

Route::get('/', 'SiteHomepageController@index');

Auth::routes();

Route::resource('/followers', 'FollowersController');
Route::resource('/following', 'FollowingsController');

Route::get('/home', 'HomeController@index');
Route::post('/tweet', 'TweetController@create');

Route::get('/edit-profile', 'EditProfileController@index');
Route::patch('/edit-profile', 'EditProfileController@update');

// Route::get('/followers', 'FollowsController@followers');
// Route::get('/following', 'FollowsController@following');
// Route::post('/follow/user/{username}', 'FollowsController@create');
// Route::delete('/unfollow/user/{username}', 'FollowsController@destroy');
// Route::get('/{username}', 'ProfileController@index');
// Route::get('/{username}/followers', 'FollowsController@followersForUser');
// Route::get('/{username}/following', 'FollowsController@followingForUser');
Route::get('/{username}', 'ProfileController@index');
