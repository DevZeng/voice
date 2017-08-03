<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['middleware'=>['api']],function (){
   Route::post('V1/moment/add','V1\MomentController@addMoment');
   Route::post('V1/moment/get','V1\MomentController@testGet');
   Route::post('V1/moment/set','V1\MomentController@testSet');
   Route::get('/v1/login/test','V1\UserController@test');
   Route::get('/v1/moments','V1\MomentController@getMoments');
   Route::get('/v1/moment/{id}','V1\MomentController@getMoment');
});
