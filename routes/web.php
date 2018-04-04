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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::post('store_user_data',['uses'=>'FacebookPageController@saveFacebookUser']);
Route::post('store_page_data',['uses'=>'FacebookPageController@saveFbUserPageData']);
Route::any('getformcountbypage',['uses'=>'FacebookPageController@getFormCountByPage']);

Route::get('{user_id}/insertUser', 'ApiController@index')->name('insertUser');

Auth::routes();

Route::get('/', function () {
    return redirect()->route('login');
});
Route::any('/', 'HomeController@index')->name('home');

Route::any('/getAnalytics', 'HomeController@getAnalytics')->name('getAnalytics');

Route::post('/boardcast', 'HomeController@boadcast')->name('boardcast');

Route::any('/deleteusers', 'HomeController@deleteUserRecords')->name('deleteusers');
