<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\CommentPost;
use App\Http\Requests\MomentPost;
use App\Http\Controllers\Controller;
use App\Models\Moment;
use App\Models\MomentCollect;
use App\Models\MomentComment;
use App\Models\MomentImage;
use App\Models\MomentLike;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class MomentController extends Controller
{
    //
    public function addMoment(MomentPost $request)
    {
        $anonymous = $request->get('anonymous',0);
        $moment = new Moment();
        $moment->content = $request->get('content');
        $moment->type = $request->get('type');
        if ($moment->type==1){
            $moment->state = 1;
        }
        $moment->anonymous = $anonymous;
        $moment->auth_id = getUserId($request->get('_token'));
        $moment->warehouse_id = getWarehouseId($request->get('_token'));
        $img = $request->get('images');
//        $img = empty($img)?[]:explode(';',$img);
        if ($moment->save()){
            if (!empty($img)) {
                for ($i = 0; $i<count($img);$i++){
                    $image = new MomentImage();
                    $image->url = setUrl($img[$i]);
                    $image->base_url = $img[$i];
                    $image->moment_id = $moment->id;
                    $image->save();
                }
            }
            if ($moment->type==2){
                $number = self::makePaySn(getUserId($request->get('_token')));
                return response()->json([
                    'code'=>'200',
                    'msg'=>'success',
                    'data'=>[
                        'number'=>$number,
                        'moment_id'=>$moment->id
                    ]
                ]);
            }else{
                return response()->json([
                    'code'=>'200',
                    'msg'=>'success',
                    'data'=>''
                ]);
            }

        }
    }
    public function testSet()
    {
        $user = ['da'=>'2','dsaf'=>'23'];
        \Redis::set('user',serialize($user));
    }
    public function testGet()
    {
        $userId = getUserId('daf');
        if ($userId){
            echo $userId;
        }else{
            echo 'Not Found';
        }
    }
    public function getMoments()
    {
        $limit = 10;
        $page = Input::get('page',1);
        $type = Input::get('type',1);
        $warehouse = Warehouse::find(getWarehouseId(Input::get('_token')));
        $moments = $warehouse->moments()->where('top','=','0')->where('type','=',$type)->where('state','=','2')->limit($limit)->offset(($page-1)*$limit)->get();
        $this->formatMoments($moments);
        return response()->json([
            'code'=>'200',
            'msg'=>'success',
            'data'=>$moments
        ]);
    }
    public function getTopMoments()
    {
        $warehouse = Warehouse::find(getWarehouseId(Input::get('_token')));
        $type = Input::get('type',2);
        $moments = $warehouse->moments()->where('top','=','1')->where('type','=',$type)->where('state','=','2')->get();
        $this->formatMoments($moments);
        return response()->json([
            'code'=>'200',
            'msg'=>'success',
            'data'=>$moments
        ]);
    }
    public function formatMoments(&$moments)
    {
        if (empty($moments)){
            return false;
        }
        $length = count($moments);
        for ($i = 0 ;$i <$length ;$i++){
            if ($moments[$i]->anonymous!=1){
                $user = $moments[$i]->user()->first();
                $moments[$i]->avatar = $user->avatarUrl;
                $moments[$i]->userName = $user->nickname;
            }
            $moments[$i]->images = $moments[$i]->images()->get();
            $moments[$i]->commentCount = $moments[$i]->comments()->count();
            $moments[$i]->likeCount = $moments[$i]->likes()->count();
            $moments[$i]->isLike= $moments[$i]->likes()->where('auth_id','=',getUserId(Input::get('_token')))->count();
        }
    }
    public function addComment(Request $request)
    {
        $comment = new MomentComment();
        $comment->moment_id = $request->get('moment_id');
        $comment->content = $request->get('content');
        $comment->comment_id = $request->get('comment_id',0);
        $comment->auth_id = getUserId($request->get('_token'));
        if ($comment->save()){
            return response()->json([
                'code'=>'200',
                'msg'=>'success'
            ]);
        }
    }
    public function collectMoment($moment_id)
    {
        $auth_id = getUserId(Input::get('_token'));
        $warehouse_id = getWarehouseId(Input::get('_token'));
        $collect = MomentCollect::where([
            'auth_id'=>$auth_id,
            'moment_id'=>$moment_id,
            'warehouse_id'=>$warehouse_id
        ])->first();
        if (empty($collect)){
            $collect = new MomentCollect();
            $collect->auth_id = $auth_id;
            $collect->moment_id = $moment_id;
            $collect->warehouse_id = $warehouse_id;
            $collect->save();
            return response()->json([
                'code'=>'200',
                'msg'=>'success',
                'data'=>'1'
            ]);
        }
        if($collect->delete()){
            return response()->json([
                'code'=>'200',
                'msg'=>'success',
                'data'=>'0'
            ]);
        }

    }
    public function likeMoment($moment_id)
    {
        $auth_id = getUserId(Input::get('_token'));
        $warehouse_id = getWarehouseId(Input::get('_token'));
        $like = MomentLike::where([
            'auth_id'=>$auth_id,
            'moment_id'=>$moment_id,
            'warehouse_id'=>$warehouse_id
        ])->first();
        if (empty($like)){
            $like = new MomentLike();
            $like->auth_id = $auth_id;
            $like->moment_id = $moment_id;
            $like->warehouse_id = $warehouse_id;
            $like->save();
            return response()->json([
                'code'=>'200',
                'msg'=>'success',
                'data'=>'1'
            ]);
        }
        if($like->delete()){
            return response()->json([
                'code'=>'200',
                'msg'=>'success',
                'data'=>'0'
            ]);
        }
    }
    public function getMoment($id)
    {
        $moment = Moment::find($id);
        if ($moment->anonymous!=1){
            $user = $moment->user()->first();
            $moment->avatar = $user->avatarUrl;
            $moment->userName = $user->nickname;
        }
        $hotComment = $moment->comments()->where('comment_id','=','0')->orderBy('like','DESC')->limit(3)->get();
        $newsComment = $moment->comments()->where('comment_id','=','0')->orderBy('id','DESC')->limit(10)->get();
        $this->formatComments($hotComment);
        $this->formatComments($newsComment);
        $moment->hotComments = $hotComment;
        $moment->images = $moment->images()->get();
        $moment->newComments = $newsComment;
        $moment->commentCount = $moment->comments()->count();
        $moment->isLike= $moment->likes()->where('auth_id','=',getUserId(Input::get('_token')))->count();
        $moment->isCollect= $moment->collects()->where('auth_id','=',getUserId(Input::get('_token')))->count();
        return response()->json([
            'code'=>'200',
            'msg'=>'success',
            'data'=>$moment
        ]);
    }
    public function formatComments(&$comments)
    {
        $length = count($comments);
        if ($length==0){
            return false;
        }
        for ($i = 0; $i<$length ;$i++){
            $user = $comments[$i]->user()->first();
            $comments[$i]->avatar = $user->avatarUrl;
            $comments[$i]->userName = $user->nickname;
        }
    }
    public function getComments($id)
    {
        $page = Input::get('page',1);
        $limit = 10;
        $comments = MomentComment::where('moment_id','=',$id)->where('comment_id','=','0')->limit($limit)->offset(($page-1)*$limit)->orderBy('id','DESC')->get();
        return response()->json([
            'code'=>'200',
            'msg'=>'success',
            'data'=>$comments
        ]);
    }
    public function likeComments($id)
    {
        $comment = MomentComment::find($id);
        if (empty($comment)){
            return response()->json([
                'code'=>'400',
                'msg'=>'Not Found'
            ]);
        }
        $comment->like();
        if ($comment->save()){
            return response()->json([
                'code'=>'200',
                'msg'=>'success'
            ]);
        }
    }
}
