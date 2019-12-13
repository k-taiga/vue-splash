<?php

// SPAなのでURL以外のリクエストに対してはindexの画面を返す
// 画面の遷移はVueRouterが制御
Route::get('/', function () {
    return view('welcome');
});
