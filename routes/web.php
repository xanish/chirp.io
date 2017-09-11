<?php

Route::get('/', 'SiteHomePageController@index');
Route::get('/home', 'HomeController@index');

Auth::routes();

Route::get('/search', 'SearchController@search');
Route::get('/search/{search}', 'SearchController@results');
Route::get('/popular_tags', 'TagController@popular_tags');
Route::get('/tag/{tag}/tweets', 'TagController@index');
Route::get('/tag/{tag}', 'TagController@tags');

Route::post('/tweet', 'TweetController@create');

Route::get('/gettweets', 'ProfileController@fetchTweets');
Route::get('/getfeed', 'HomeController@getfeed');
Route::get('/getsearchbytagtweets', 'TagController@tweets');

Route::post('/like/{tweet_id}', 'LikesController@like');
Route::delete('/unlike/{tweet_id}', 'LikesController@unlike');

Route::get('/edit-profile', 'EditProfileController@index');
Route::patch('/edit-profile', 'EditProfileController@update');

Route::post('/follow/{id}', 'FollowsController@follow');
Route::delete('/unfollow/{id}', 'FollowsController@unfollow');

Route::get('/{username}', 'ProfileController@profile');
Route::get('/{username}/followers', 'ProfileController@followers');
Route::get('/{username}/following', 'ProfileController@following');

Route::get('/verifyemail/{token}', 'Auth\RegisterController@verify');
