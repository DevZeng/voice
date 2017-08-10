<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppPost;
use App\Libraries\WxNotify;
use App\Libraries\WxPay;
use App\Models\Advert;
use App\Models\Moment;
use App\Models\MomentComment;
use App\Models\OAuthUser;
use App\Models\Order;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class WarehouseController extends Controller
{
    //
    public function getAdverts()
    {
//        $warehouse = Warehouse::where('app_id','=',Input::get('app_id'))->first();
        $adverts = Advert::where('warehouse_id','=',getWarehouseId(Input::get('_token')))->get();
        if (!empty($adverts)){
            for ($i=0;$i<count($adverts);$i++){
                $adverts[$i]->url = setUrl($adverts[$i]->url);
                $adverts[$i]->link = setUrl($adverts[$i]->link);
            }
        }
        return response()->json([
            'code'=>'200',
            'msg'=>'success',
            'data'=>$adverts
        ]);
    }

    public function momentReviewList()
    {
        $moments = Moment::where([
            'state'=>'1',
            'warehouse_id'=>session('warehouse_id')
        ])->paginate(10);
        $warehouses = Auth::user()->warehouses()->get();
        return view('momentReview',['warehouses'=>$warehouses,'moments'=>$moments]);
    }
    public function momentPassList()
    {
        $moments = Moment::where([
            'state'=>'2',
            'warehouse_id'=>session('warehouse_id')
        ])->paginate(10);
        $warehouses = Auth::user()->warehouses()->get();
        return view('momentPass',['warehouses'=>$warehouses,'moments'=>$moments]);
    }
    public function advertList()
    {
        $adverts = Advert::where([
            'warehouse_id'=>session('warehouse_id')
        ])->get();
        $warehouses = Auth::user()->warehouses()->get();
        return view('advertList',['warehouses'=>$warehouses,'adverts'=>$adverts]);
    }
    public function addAdvertPage()
    {
        $warehouses = Auth::user()->warehouses()->get();
        return view('addAdvert',['warehouses'=>$warehouses]);
    }
    public function delAdverts()
    {
        $adverts = Input::get('delList');
        if (empty($adverts)){
            return response()->json([
                'code'=>'400',
                'msg'=>'参数不能为空!'
            ]);
        }
        for ($i=0;$i<count($adverts);$i++){
            $advert = Advert::find($adverts[$i]);
            $advert->delete();
        }
        return response()->json([
            'code'=>'200',
            'msg'=>'success'
        ]);
    }
    public function momentsPass()
    {
        $list = Input::get('reviewList');
        if (empty($list)){
            return response()->json([
                'code'=>'400',
                'msg'=>'参数不能为空!'
            ]);
        }
        for ($i=0;$i<count($list);$i++){
            $moment = Moment::find($list[$i]);
            $moment->state=2;
            $warehouse = Warehouse::find($moment->warehouse_id);
            $user = OAuthUser::find($moment->auth_id);
            $wxnotify = new WxNotify($warehouse->app_id,$warehouse->secret);
            $data = [
                "touser"=>$user->open_id,
                "template_id"=>$warehouse->template_id,
                "form_id"=> $moment->notify_id,
                "page"=>"pages/index/index",
                "data"=>[
                    "keyword1"=>[
                        "value"=>mb_substr($moment->content,0,20)
                    ],
                    "keyword2"=>[
                        "value"=>date('Y-m-d H:i:s',strtotime($moment->created_at))
                    ],
                    "keyword3"=>[
                        "value"=>"通过"
                    ]
            ]
            ];
            $wxnotify->setAccessToken();
            $data = $wxnotify->send(json_encode($data));
            $moment->save();
        }
        return response()->json([
            'code'=>'200',
            'msg'=>'success'
        ]);
    }
    public function momentsDel()
    {
        $list = Input::get('delList');
        if (empty($list)){
            return response()->json([
                'code'=>'400',
                'msg'=>'参数不能为空!'
            ]);
        }
        for ($i=0;$i<count($list);$i++){
            $moment = Moment::find($list[$i]);
            $moment->comments()->delete();
            $moment->likes()->delete();
            $moment->images()->delete();
            $moment->delete();
        }
        return response()->json([
            'code'=>'200',
            'msg'=>'success'
        ]);
    }
    public function banUsers()
    {
        $list = Input::get('banList');
        if (empty($list)){
            return response()->json([
                'code'=>'400',
                'msg'=>'参数不能为空!'
            ]);
        }
        for ($i=0;$i<count($list);$i++){
            $user = OAuthUser::find($list[$i]);
            $user->ban=1;
            $user->save();
        }
        return response()->json([
            'code'=>'200',
            'msg'=>'success'
        ]);
    }
    public function unBanUsers()
    {
        $list = Input::get('banList');
        if (empty($list)){
            return response()->json([
                'code'=>'400',
                'msg'=>'参数不能为空!'
            ]);
        }
        for ($i=0;$i<count($list);$i++){
            $user = OAuthUser::find($list[$i]);
            $user->ban=0;
            $user->save();
        }
        return response()->json([
            'code'=>'200',
            'msg'=>'success'
        ]);
    }
    public function momentsRefuse()
    {
        $list = Input::get('reviewList');
        if (empty($list)){
            return response()->json([
                'code'=>'400',
                'msg'=>'参数不能为空!'
            ]);
        }
        for ($i=0;$i<count($list);$i++){
            $moment = Moment::find($list[$i]);
            $moment->state=3;
            $warehouse = Warehouse::find($moment->warehouse_id);
            $user = OAuthUser::find($moment->auth_id);
            $wxnotify = new WxNotify($warehouse->app_id,$warehouse->secret);
            $data = [
                "touser"=>$user->open_id,
                "template_id"=>$warehouse->template_id,
                "form_id"=> $moment->notify_id,
                "page"=>"pages/index/index",
                "data"=>[
                    "keyword1"=>[
                        "value"=>mb_substr($moment->content,0,20)
                    ],
                    "keyword2"=>[
                        "value"=>date('Y-m-d H:i:s',strtotime($moment->created_at))
                    ],
                    "keyword3"=>[
                        "value"=>"拒绝"
                    ]
                ]
            ];
            $wxnotify->setAccessToken();
            $wxnotify->send(json_encode($data));

            if($moment->type==2){
                $number = self::makePaySn(rand(1,9));
                $path = base_path().'/public/';
                $wxpay = new WxPay($warehouse->app_id,$warehouse->m_id,$warehouse->api_key);
                $order = Order::where('moment_id','=',$moment->id)->first();
                $data = $wxpay->refund($order->transaction_id,$number,10*100,10*100,$warehouse->m_id,$path.$warehouse->sslCert,
                    $path.$warehouse->sslKey,$path.$warehouse->caInfo);
                if ($data['return_code']=='FAIL'){
                    $order->state = 4;
                    $order->remark = $data['return_msg'];
                    $order->save();
                }else{
                    if ($data['result_code']=='FAIL'){
                        $order->state = 3;
                        $order->remark = $data['err_code'].$data['err_code_des'];
                        $order->save();
                    }else{
                        $order->state = 2;
                        $order->remark = $data['return_msg'];
                        $order->save();
                    }
                }
            }

            $moment->save();
        }
        return response()->json([
            'code'=>'200',
            'msg'=>'success'
        ]);
    }
    public function addAdvert()
    {
        $remark = Input::get('remark');
        $link = Input::get('imglink');
        $url = Input::get('imgurl');
        if (empty($url)||empty($link)){
            return redirect()->back()->with('status','参数错误！');
        }
        $advert = new Advert();
        $advert->warehouse_id = session('warehouse_id');
        $advert->remark = empty($remark)?'':$remark;
        $advert->url = $url;
        $advert->link = $link;
        if ($advert->save()){
            return redirect()->back()->with('status','添加成功！');
        }
    }
    public function delComment($id)
    {
        $comment = MomentComment::find($id);
        if ($comment->delete()){
            return redirect()->back()->with('status','操作成功！');
        }
    }
    public function showMoment($id)
    {
        $moment = Moment::find($id);
        $images = $moment->images()->get();
        $comments = $moment->comments()->get();
        $warehouses = Auth::user()->warehouses()->get();
        return view('momentDetail',['moment'=>$moment,'comments'=>$comments,'images'=>$images,'warehouses'=>$warehouses]);
    }
    public function delMoment($id)
    {
        $moment = Moment::find($id);
        $moment->comments()->delete();
        $moment->likes()->delete();
        $moment->images()->delete();
        $moment->delete();
        return redirect()->back()->with('status','操作成功！');
    }
    public function test()
    {
        $id = Input::get('id');
        $moment = Moment::find($id);
        $warehouse = Warehouse::find($moment->warehouse_id);
        $user = OAuthUser::find($moment->auth_id);
        $wxnotify = new WxNotify($warehouse->app_id,$warehouse->secret);
        $data = [
            "touser"=>$user->open_id,
            "template_id"=>$warehouse->template_id,
            "form_id"=> $moment->notify_id,
            "page"=>"pages/index/index",
            "data"=>[
                "keyword1"=>[
                    "value"=>'你发布的动态已被审核'
                ],
                "keyword2"=>[
                    "value"=>date('Y-m-d H:i:s',strtotime($moment->created_at))
                ],
                "keyword3"=>[
                    "value"=>"通过"
                ]
            ]
        ];
        $wxnotify->setAccessToken();
        $err = $wxnotify->send(json_encode($data));
        var_dump(json_encode($data));
        dd($err);
    }
    public function delAdvert($id)
    {
        $advert = Advert::find($id);
        if ($advert->delete()){
            return back()->with('status','操作成功！');
        }
    }
    public function addApp()
    {
        $warehouses = Auth::user()->warehouses()->get();
        return view('appAdd',['warehouses'=>$warehouses]);
    }
    public function addAppPost(AppPost $request)
    {
        $warehouse = new Warehouse();
        $warehouse->name = $request->get('name');
        $warehouse->app_id = $request->get('app_id');
        $warehouse->secret = $request->get('secret');
        $warehouse->user_id = Auth::id();
        $warehouse->m_id = $request->get('mch_id');
        $warehouse->api_key = $request->get('api_key');
        $warehouse->template_id = $request->get('template_id');
        $warehouse->caInfo = $request->get('cainfo');
        $warehouse->sslCert = $request->get('sslcert');
        $warehouse->sslKey = $request->get('sslkey');
        if ($warehouse->save()){
            return redirect()->back()->with('status','操作成功！');
        }
    }
    public function listApp()
    {
        $warehouses = Auth::user()->warehouses()->get();
        return view('appList',['warehouses'=>$warehouses]);
    }
    public function modifyAppPage()
    {
        $id = Input::get('id');
        $warehouses = Auth::user()->warehouses()->get();
        $info = Warehouse::find($id);
        return view('appModify',['warehouses'=>$warehouses,'info'=>$info]);
    }
    public function modifyApp(AppPost $request)
    {
        $id = Input::get('id');
        $warehouse = Warehouse::find($id);
        $warehouse->name = $request->get('name');
        $warehouse->app_id = $request->get('app_id');
        $warehouse->secret = $request->get('secret');
        $warehouse->user_id = Auth::id();
        $warehouse->m_id = $request->get('mch_id');
        $warehouse->api_key = $request->get('api_key');
        $warehouse->template_id = $request->get('template_id');
        $warehouse->caInfo = $request->get('cainfo');
        $warehouse->sslCert = $request->get('sslcert');
        $warehouse->sslKey = $request->get('sslkey');
        if ($warehouse->save()){
            return redirect()->back()->with('status','操作成功！');
        }
    }
}
