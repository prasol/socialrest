<?php

Route::group(['middleware' => 'auth.simple'], function () {
    Route::get('followers', 'FollowersController@index');
    Route::post('followers', 'FollowersController@store');
    Route::post('followers/{id}', 'FollowersController@accept');
    Route::delete('followers/{id}', 'FollowersController@decline');

    Route::get('friends', 'FriendsController@index');
});
