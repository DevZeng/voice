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
    return view('welcome');
});

//Auth::routes();
Route::get('/login',['as'=>'login','uses'=>'UserController@login']);
Route::post('/login','UserController@doLogin');
Route::get('/user/list','UserController@userList');
Route::get('/home', 'HomeController@index')->name('home');
