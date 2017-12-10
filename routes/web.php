<?php

Route::get('/', 'HomeController@showHome')->name('home');
Route::get('home', 'HomeController@showUserPage')->name('user');

Route::get('login', 'Auth\LoginController@redirectToFb')->name('login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('login/callback', 'Auth\LoginController@handleLoginCallback')->name('callback');
Route::any('deAuth', 'Auth\LoginController@handleDeAuthCallback')->name('deAuth');