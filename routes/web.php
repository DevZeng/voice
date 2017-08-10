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

Route::get('/', function () {
    return \Illuminate\Support\Facades\Redirect::route('login');
});

//Auth::routes();
Route::get('/login',['as'=>'login','uses'=>'UserController@login']);
Route::get('/test','WarehouseController@test');
Route::post('/login','UserController@doLogin');
Route::post('/upload','UploadController@uploadImage');
Route::get('/logout','UserController@logout')->name('logout');
Route::group(['middleware'=>'auth'],function (){
    Route::get('/user/list','UserController@userList');
    Route::get('/set/warehouse/{id}','UserController@setWarehouse');
    Route::get('/moment/review','WarehouseController@momentReviewList');
    Route::get('/moment/pass','WarehouseController@momentPassList');
    Route::get('/advert/list','WarehouseController@advertList');
    Route::get('/advert/add','WarehouseController@addAdvertPage');
    Route::post('/moments/pass','WarehouseController@momentsPass');
    Route::post('/moments/refuse','WarehouseController@momentsRefuse');
    Route::post('/moments/del','WarehouseController@momentsDel');
    Route::get('/moment/del/{id}','WarehouseController@delMoment');
    Route::get('/comment/del/{id}','WarehouseController@delComment');
    Route::get('/moment/detail/{id}','WarehouseController@showMoment');
    Route::post('/adverts/del','WarehouseController@delAdverts');
    Route::get('/advert/del/{id}','WarehouseController@delAdvert');
    Route::post('/user/ban','WarehouseController@banUsers');
    Route::post('/user/restore','WarehouseController@unBanUsers');
    Route::post('/advert/add','WarehouseController@addAdvert');
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/app/add','WarehouseController@addApp');
    Route::post('/app/add','WarehouseController@addAppPost');
    Route::get('/app/modify','WarehouseController@modifyAppPage');
    Route::post('/app/modify','WarehouseController@modifyApp');
    Route::get('/app/list','WarehouseController@listApp');
});
