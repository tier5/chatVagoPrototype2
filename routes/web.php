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
Route::post('store_page_access_token_data',['uses'=>'FacebookPageController@saveFacebookUserPageToken']);
Route::post('store_page_data',['uses'=>'FacebookPageController@saveFbUserPageData']);
Route::get('get_page_data',['uses'=>'FacebookPageController@getFacebookPageData']);
Route::any('getformcountbypage',['uses'=>'FacebookPageController@getFormCountByPage']);

Route::get('{user_id}/insertUser', 'ApiController@index')->name('insertUser');

Auth::routes();

Route::get('/', function () {
    return redirect()->route('login');
});

//Route to show Pages
Route::get('/messengerCode', 'MessengerCodeController@index')->name('messengerCode');
Route::get('/broadcastList', 'BoardcastController@index')->name('broadcastList');
// Route for each operation 
// 
Route::any('/', 'HomeController@index')->name('home');

Route::any('/analytics/{page_id?}', 'AnalyticsController@index')->name('analytics');

Route::post('/boardcast', 'BoardcastController@boadcast')->name('boardcast');

Route::post('/messenger_image', 'MessengerCodeController@getMessengerImage')->name('messenger_image');

Route::any('/deleteusers', 'BoardcastController@deleteUserRecords')->name('deleteusers');
