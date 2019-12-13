<?php

// SPAなのでURL以外のリクエストに対してはindexの画面を返す
// 画面の遷移はVueRouterが制御
Route::get('/{any?}', function () {
	return view('index');
	// whereでパラメータを正規表現でバリデーション
	// ここでは+なのでなにが来てもいい
})->where('any', '.+');
