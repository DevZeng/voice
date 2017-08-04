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
   Route::post('v1/moment/add','V1\MomentController@addMoment');
   Route::get('/v1/login/test','V1\UserController@test');
   Route::post('/v1/login','V1\UserController@OAuthLogin');
   Route::get('/v1/moments','V1\MomentController@getMoments');
   Route::get('/v1/moment/{id}','V1\MomentController@getMoment');
   Route::post('/v1/moment/like/{id}','V1\MomentController@likeMoment');
   Route::post('/v1/moment/collect/{id}','V1\MomentController@collectMoment');
   Route::post('/v1/moment/comment/add','V1\MomentController@addComment');
   Route::post('/v1/pay/notify/add','V1\OrderController@payNotify');
   Route::post('/v1/pay','V1\OrderController@pay');
   Route::post('/v1/upload','UploadController@uploadImage');
});
