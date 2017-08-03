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
        $moment->anonymous = $anonymous;
        $moment->auth_id = getUserId($request->get('_token'));
        $moment->warehouse_id = getWarehouseId($request->get('_token'));
        $img = $request->get('images');
        $img = empty($img)?[]:explode(';',$img);
        if ($moment->save()){
            if (!empty($img)) {
                for ($i = 0; $i<count($img);$i++){
                    $image = new MomentImage();
                    $image->url = $img[$i];
                    $image->moment_id = $moment->id;
                    $image->save();
                }
            }
            return response()->json([
                'code'=>'200',
                'msg'=>'success'
            ]);
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
        $moments = $warehouse->moments()->where('type','=',$type)->where('state','=','2')->limit($limit)->offset(($page-1)*$limit)->get();
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
            $moments[$i]->commentCount = $moments[$i]->comments()->count();
            $moments[$i]->likeCount = $moments[$i]->likes()->count();
        }
    }
    public function addComment(CommentPost $request)
    {
        $comment = new MomentComment();
        $comment->moment_id = $request->get('moment_id');
        $comment->content = $request->get('content');
        $comment->auth_id = getUserId($request->get('_token'));
        if ($comment->save()){
            return response()->json([
                'code'=>'200',
                'msg'=>'success'
            ]);
        }
    }
    public function likeMoment($id)
    {
        $momentLike = new MomentLike();
        $momentLike->auth_id = getUserId(Input::get('_token'));
        $momentLike->moment_id = $id;
        if ($momentLike->save()){
            return response()->json([
                'code'=>'200',
                'msg'=>'success'
            ]);
        }
    }
    public function collectMoment($id)
    {
        $momentCollect = new MomentCollect();
        $momentCollect->moment_id = $id;
        $momentCollect->auth_id = getUserId(Input::get('_token'));
        if ($momentCollect->save()){
            return response()->json([
                'code'=>'200',
                'msg'=>'success'
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
        $hotComment = $moment->comments()->orderBy('like','DESC')->limit(3)->get();
        $newsComment = $moment->comments()->orderBy('id','DESC')->limit(10)->get();
        $this->formatComments($hotComment);
        $this->formatComments($newsComment);
        $moment->hotComments = $hotComment;
        $moment->newComments = $newsComment;
        $moment->commentCount = $moment->comments()->count();
        $moment->like= $moment->likes()->where('auth_id','=',getUserId(Input::get('_token')))->count();
        $moment->collect= $moment->collects()->where('auth_id','=',getUserId(Input::get('_token')))->count();
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
}
