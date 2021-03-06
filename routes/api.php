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
Route::post('/v1/pay/notify','V1\OrderController@payNotify');
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['middleware'=>['api']],function (){
    Route::group(['middleware'=>['ban']],function (){
        Route::post('v1/moment/add','V1\MomentController@addMoment');
        Route::post('/v1/moment/comment/add','V1\MomentController@addComment');
        Route::post('/v1/moment/comment/reply','V1\MomentController@replyComment');
    });
    Route::group(['middleware'=>['token']],function (){
        Route::get('/v1/moments','V1\MomentController@getMoments');
        Route::get('/v1/moments/top','V1\MomentController@getTopMoments');
        Route::post('/v1/moment/like/{id}','V1\MomentController@likeMoment');
        Route::post('/v1/moment/collect/{id}','V1\MomentController@collectMoment');
        Route::get('/v1/reply/like/{id}','V1\MomentController@replyLike');
        Route::get('/v1/comments/{id}','V1\MomentController@getComments');
        Route::get('/v1/comment/{id}','V1\MomentController@getComment');
        Route::get('/v1/comment/like/{id}','V1\MomentController@likeComments');
        Route::post('/v1/pay','V1\OrderController@pay');
        Route::get('/v1/adverts','WarehouseController@getAdverts');
        Route::get('/v1/my/moments','V1\UserController@getMoments');
        Route::get('/v1/my/moments/collect','V1\UserController@getCollects');
        Route::get('/v2/my/notify/comments','V2\UserController@getCommentNotifies');
        Route::get('/v2/notify/read/{id}','V2\UserController@readCommentNotify');
        Route::get('/v2/notify/Unread/count','V2\UserController@getUnReadCount');
        Route::post('/v2/moment/video/add','V2\MomentController@addMomentVideo');
    });
   Route::get('/v1/login/test','V1\UserController@test');
   Route::post('/v1/login','V1\UserController@OAuthLogin');
   Route::get('/v1/token','V1\UserController@getToken');
   Route::get('/v1/moment/{id}','V1\MomentController@getMoment');
   Route::post('/v1/upload','UploadController@uploadImage');
});
