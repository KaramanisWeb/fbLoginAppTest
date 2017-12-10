<?php

Route::any('deAuth', 'Auth\LoginController@handleDeAuthCallback')->name('deAuth');

Route::group(['middleware' => 'guest'], function () {
	Route::get('/', 'HomeController@showHome')->name('home');
	Route::get('login', 'Auth\LoginController@redirectToFb')->name('login');
	Route::get('login/callback', 'Auth\LoginController@handleLoginCallback')->name('callback');
});

Route::group(['middleware' => ['auth', 'active']], function () {
	Route::get('home', 'HomeController@showUserPage')->name('user');
	Route::post('logout', 'Auth\LoginController@logout')->name('logout');
});