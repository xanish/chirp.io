<?php

Route::get('/', 'SiteHomePageController@index');
Route::get('/home', 'HomeController@index');

Auth::routes();


Route::get('/search', 'SearchController@search');
Route::get('/search/{search}', 'SearchController@results');

Route::post('/tweet', 'TweetController@create');

Route::post('/like/{tweet_id}', 'LikesController@like');
Route::delete('/unlike/{tweet_id}', 'LikesController@unlike');

Route::get('/edit-profile', 'EditProfileController@index');
Route::patch('/edit-profile', 'EditProfileController@update');

Route::post('/follow/{username}', 'FollowsController@follow');
Route::delete('/unfollow/{username}', 'FollowsController@unfollow');

Route::get('/{username}', 'ProfileController@profile');
Route::get('/{username}/followers', 'ProfileController@followers');
Route::get('/{username}/following', 'ProfileController@following');
