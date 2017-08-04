<?php

namespace App\Http\Controllers\V1;

use App\Libraries\Wxxcx;
use App\Models\MomentCollect;
use App\Models\MomentLike;
use App\Models\OAuthUser;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redis;

class UserController extends Controller
{
    //
    public function OAuthLogin(Request $request)
    {
        $warehouse = Warehouse::where('app_id','=',$request->get('app_id'))->first();
        $wxxcx = new Wxxcx($warehouse->app_id,$warehouse->secret);
        $code = $request->get('code');
        $encryptedData = $request->get('encryptedData');
        $iv = $request->get('iv');
        $sessionKey = $wxxcx->getSessionKey($code);
        $user = $wxxcx->decode($encryptedData,$iv);
        $user = json_decode($user);
        $info = OAuthUser::where('open_id','=',$user->openId)->first();
        if(empty($info)){
            $ouser = new OAuthUser();
            $ouser->nickname = $user->nickName;
            $ouser->gender = $user->gender;
            $ouser->city = $user->city;
            $ouser->province = $user->province;
            $ouser->avatarUrl = $user->avatarUrl;
            $ouser->open_id = $user->openId;
            if($ouser->save()){
                $uid = uniqid();
                $warehouse_id = $warehouse->id;
                $data = [
                    'user_id'=>$ouser->id,
                    'warehouse_id'=>$warehouse_id
                ];
                Redis::set($uid,serialize($data));
                Redis::expire($uid,3600);
                return response()->json([
                    'code'=>'200',
                    'msg'=>'success',
                    'data'=>$uid
                ]);
            }
        }else{
            $uid = uniqid();
            $warehouse_id = $warehouse->id;
            $data = [
                'user_id'=>$info->id,
                'warehouse_id'=>$warehouse_id
            ];
            Redis::set($uid,serialize($data));
            Redis::expire($uid,3600);
            return response()->json([
                'code'=>'200',
                'msg'=>'success',
                'data'=>$uid
            ]);
        }
    }
    public function test()
    {
        $user = '{"openId":"oSRIL0TxYd83IDSrs-pzH4uLVkuY",
        "nickName":"SHINING诗永"
        ,"gender":1,
        "language":"zh_CN",
        "city":"Guangzhou",
        "province":"Guangdong",
        "country":"CN",
        "avatarUrl":"http://wx.qlogo.cn/mmopen/vi_32/wVJLKmibyYdoMrMFFOrYu5sUiabBnYmdhW1jJgUbicKM20rYCOAicaKOkggJ9YnpaibG4doem7MICbkTg625LsWDBDg/0",
        "unionId":"odE-C1SIV5DF9p4pUaJ4g345Tr1I"
        ,"watermark":{"timestamp":1497495793,"appid":"wx3de43218a4513fba"}}';
        $user = json_decode($user);
        $info = OAuthUser::where('open_id','=',$user->openId)->first();
        $warehouse_id = Warehouse::where('app_id','=','wxdd05e7ae4cf78bea')->pluck('id')->first();
        if(empty($info)){
            $ouser = new OAuthUser();
            $ouser->nickname = $user->nickName;
            $ouser->gender = $user->gender;
            $ouser->city = $user->city;
            $ouser->province = $user->province;
            $ouser->avatarUrl = $user->avatarUrl;
            $ouser->open_id = $user->openId;
            $ouser->warehouse_id = $warehouse_id;
            if($ouser->save()){
                $uid = uniqid();

                $data = [
                    'user_id'=>$ouser->id,
                    'warehouse_id'=>$warehouse_id
                ];
                Redis::set($uid,serialize($data));
                Redis::expire($uid,3600);
                return response()->json([
                    'code'=>'200',
                    'msg'=>'success',
                    'data'=>$uid
                ]);
            }
        }else{
            $uid = uniqid();
            $data = [
                'user_id'=>$info->id,
                'warehouse_id'=>$warehouse_id
            ];
            Redis::set($uid,serialize($data));
            Redis::expire($uid,3600);
            return response()->json([
                'code'=>'200',
                'msg'=>'success',
                'data'=>$uid
            ]);
        }
    }

}
