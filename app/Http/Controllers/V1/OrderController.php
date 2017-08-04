<?php

namespace App\Http\Controllers\V1;

use App\Libraries\WxPay;
use App\Models\Moment;
use App\Models\OAuthUser;
use App\Models\Order;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class OrderController extends Controller
{
    //
    public function pay()
    {
        $warehouse_id = getWarehouseId(Input::get('_token'));
        $auth_id = getUserId(Input::get('_token'));
        $warehouse = Warehouse::find($warehouse_id);
        $auth = OAuthUser::find($auth_id);
        $moment = Moment::find(Input::get('moment_id'));
        $payment = new WxPay($warehouse->app_id,$warehouse->m_id,$warehouse->api_key,$auth->open_id);
        $order = new Order();
        $order->moment_id = $moment->id;
        $order->auth_id = $auth_id;
        $order->warehouse_id = $warehouse_id;
        $order->number = Input::get('number');
        $data = $payment->pay(Input::get('number'),'发布动态',(10*100));
        $swap = json_decode($data);
        $order->prepay_id = $swap->package['prepay_id'];
        if ($order->save()){
            return json_encode([
                'code'=>'200',
                'msg'=>'success',
                'data'=>$data
            ]);
        }
    }
    public function payNotify(Request $request)
    {
        $data = $request->getContent();
        $wx = WxPay::xmlToArray($data);
        $warehouse = Warehouse::where(['m_id'=>$wx['mch_id']])->first();
        $wspay = new WxPay($warehouse->app_id,$warehouse->m_id,$warehouse->api_key,$wx['openid']);
        $data = [
            'appid'=>$wx['appid'],
            'cash_fee'=>$wx['cash_fee'],
            'bank_type'=>$wx['bank_type'],
            'fee_type'=>$wx['fee_type'],
            'is_subscribe'=>$wx['is_subscribe'],
            'mch_id'=>$wx['mch_id'],
            'nonce_str'=>$wx['nonce_str'],
            'openid'=>$wx['openid'],
            'out_trade_no'=>$wx['out_trade_no'],
            'result_code'=>$wx['result_code'],
            'return_code'=>$wx['return_code'],
            'time_end'=>$wx['time_end'],
            'total_fee'=>$wx['total_fee'],
            'trade_type'=>$wx['trade_type'],
            'transaction_id'=>$wx['transaction_id']
        ];
        $sign = $wspay->getSign($data);
        if ($sign == $wx['sign']){
            $order = Order::where(['number'=>$wx['out_trade_no']])->first();
            if ($order->state==0){
                $order->state = 1;
                $order->transaction_id = $wx['transaction_id'];
                if ($order->save()){
                    return 'SUCCESS';
                }
            }

        }
        return 'ERROR';
    }
}
