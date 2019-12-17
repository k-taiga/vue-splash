<?php

// 会員登録のルート定義
Route::post('/register', 'Auth\RegisterController@register')->name('register');

// ログイン
Route::post('/login', 'Auth\LoginController@login')->name('login');

// ログアウト
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

// ログインしているユーザーを返すルート ただログインユーザーを返すだけなので、コントローラーじゃなくルーティングで記述
Route::get('/user', function() {
	// いなければnullを返すがHTTPレスポンスに変わるときに""になる
	return Auth::user();
})->name('user');

// 写真のPOST
Route::post('/photos', 'PhotoController@create')->name('photo.create');