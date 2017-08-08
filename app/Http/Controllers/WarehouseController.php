<?php

namespace App\Http\Controllers;

use App\Libraries\WxNotify;
use App\Models\Advert;
use App\Models\Moment;
use App\Models\MomentComment;
use App\Models\OAuthUser;
use App\Models\Warehouse;
use Illuminate\Http\Request;
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
                "touser"=>$user->nickname,
                "template_id"=>$warehouse->template_id,
                "page"=>"/pages/index/index",
                "data"=>[
                    "keyword1"=>[
                        "DATA"=>mb_substr($moment->content,0,50)
                    ],
                    "keyword2"=>[
                        "DATA"=>$moment->created_at
                    ],
                    "keyword3"=>[
                        "DATA"=>"通过"
                    ]
            ]
            ];
            $wxnotify->setAccessToken();
            $wxnotify->send(json_encode($data));
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
            $moment->state=2;
            $warehouse = Warehouse::find($moment->warehouse_id);
            $user = OAuthUser::find($moment->auth_id);
            $wxnotify = new WxNotify($warehouse->app_id,$warehouse->secret);
            $data = [
                "touser"=>$user->nickname,
                "template_id"=>$warehouse->template_id,
                "page"=>"/pages/index/index",
                "data"=>[
                    "keyword1"=>[
                        "DATA"=>mb_substr($moment->content,0,50)
                    ],
                    "keyword2"=>[
                        "DATA"=>$moment->created_at
                    ],
                    "keyword3"=>[
                        "DATA"=>"通过"
                    ]
                ]
            ];
            $wxnotify->setAccessToken();
            $wxnotify->send(json_encode($data));
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
            "page"=>"/pages/index/index",
            "data"=>[
                "keyword1"=>[
                    "value"=>'你发布的动态已被审核'
                ],
                "keyword2"=>[
                    "value"=>$moment->created_at
                ],
                "keyword3"=>[
                    "value"=>"通过"
                ]
            ]
        ];
        $wxnotify->setAccessToken();
        $err = $wxnotify->send(json_encode($data));
        dd($err);
    }
}
