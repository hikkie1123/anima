<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function() {
//    // $users = App\User::all();
//    // return View::make('users')->with('users', $users);
//    // ログをslackにも投稿
//    // Log::emergency('An informational message.');
// });

// 認証
Auth::routes();
Route::get('/', 'HomeController@index')->name('home');
Route::get('login/{provider}', 'Auth\SocialAccountController@redirectToProvider');
Route::get('login/{provider}/callback', 'Auth\SocialAccountController@handleProviderCallback');

// 検索
Route::post('search/{keyword}', 'SearchController@index')->name('search');

// 作品詳細
Route::get('item/{item_id}', 'ItemController@index')->name('item');

// ユーザー詳細
Route::get('user/{nickname}', 'UserController@index')->name('user');

// レビュー
Route::get('review/{review_id}', 'ReviewController@index')->name('review');
Route::post('review/store', 'ReviewController@store');

// コメント
Route::post('comment/store', 'CommentController@store');

// Ajax
Route::post('ajax', 'AjaxController@showMoreItems');