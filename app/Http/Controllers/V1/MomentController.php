<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\CommentPost;
use App\Http\Requests\MomentPost;
use App\Http\Controllers\Controller;
use App\Models\CommentReply;
use App\Models\Message;
use App\Models\Moment;
use App\Models\MomentCollect;
use App\Models\MomentComment;
use App\Models\MomentImage;
use App\Models\MomentLike;
use App\Models\MomentVideo;
use App\Models\OAuthUser;
use App\Models\Warehouse;
use App\User;
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
        $moment->notify_id = $request->get('formID');
        if ($moment->type==1){
            $count = Moment::where('auth_id','=',getUserId($request->get('_token')))->whereDate('created_at',date('Y-m-d',time()))->count();
            if ($count>=5){
                return response()->json([
                    'code'=>'400',
                    'msg'=>'超过每天发送条数限制!'
                ]);
            }
            $moment->state = 1;
        }
        $moment->anonymous = $anonymous;
        $moment->auth_id = getUserId($request->get('_token'));
        $moment->warehouse_id = getWarehouseId($request->get('_token'));
        $img = $request->get('images');
        $mov = $request->get('movies');
//        $img = empty($img)?[]:explode(';',$img);
        if ($moment->save()){
            if (!empty($img)) {
                for ($i = 0; $i<count($img);$i++){
                    if (!empty($img[$i])){
                        $image = new MomentImage();
                        $image->url = setUrl($img[$i]);
                        $image->base_url = $img[$i];
                        $image->moment_id = $moment->id;
                        $image->save();
                    }
                }
            }
            if (!empty($mov)){
                for ($j=0;$j<count($mov);$j++){
                    if (!empty($mov[$j])){
                        $movie = new MomentVideo();
                        $movie->url = setUrl($mov[$j],false);
                        $movie->base_url = $mov[$j];
                        $movie->moment_id = $moment->id;
                        $movie->save();
                    }
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
    public function getMoments()
    {
        $limit = 10;
        $page = Input::get('page',1);
        $type = Input::get('type',1);
        $warehouse = Warehouse::find(getWarehouseId(Input::get('_token')));
        $moments = $warehouse->moments()->where('top','=','0')->where('type','=',$type)->where('state','=','2')->limit($limit)->offset(($page-1)*$limit)->orderBy('updated_at','DESC')->get();
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
            $moments[$i]->videos = $moments[$i]->videos()->get();
            $moments[$i]->commentCount = $moments[$i]->comments()->count();
            $moments[$i]->likeCount = $moments[$i]->likes()->count();
            $moments[$i]->isLike= $moments[$i]->likes()->where('auth_id','=',getUserId(Input::get('_token')))->count();
        }
    }
    public function addComment(Request $request)
    {
        $comment_id = $request->get('comment_id');
        $comment_id = empty($comment_id)?0:$comment_id;
        if ($comment_id!=0){
            $comment = MomentComment::find($comment_id);
            $moment = Moment::find($comment->moment_id);
            $moment->comment();
            $moment->save();
            $reply = new CommentReply();
            $reply->auth_id = getUserId($request->get('_token'));
            $reply->reply_auth_id = $comment->auth_id;
            $reply->content = $request->get('content');
            $reply->comment_id = $comment_id;
            if ($reply->save()){
                $message = new Message();
                $message->auth_id = $reply->auth_id;
                $message->receive_id = $reply->reply_auth_id;
                $message->content = $reply->content;
                $message->comment_id = $reply->comment_id;
                $message->moment_id = $moment->id;
                $message->save();
                return response()->json([
                'code'=>'200',
                'msg'=>'success'
            ]);
            }
        }else{
            $comment = new MomentComment();
            $comment->moment_id = $request->get('moment_id');
            $moment = Moment::find($request->get('moment_id'));
            $moment->comment();
            $moment->save();
            $comment->content = $request->get('content');
            $comment->auth_id = getUserId($request->get('_token'));
            if ($comment->save()){
                $message = new Message();
                $message->auth_id = $comment->auth_id;
                $message->receive_id = $moment->auth_id;
                $message->content = $comment->content;
                $message->moment_id = $moment->id;
                $message->save();
                return response()->json([
                    'code'=>'200',
                    'msg'=>'success'
                ]);
            }
        }

    }
    public function replyComment(Request $request)
    {
        $baseReply = CommentReply::find($request->get('reply_id'));
        $reply = new CommentReply();
        $comment = MomentComment::find($baseReply->comment_id);
        $moment = Moment::find($comment->moment_id);
        $moment->comment();
        $moment->save();
//        $message = new Message();
        $reply->auth_id = getUserId($request->get('_token'));
        $reply->content = $request->get('content');
        $reply->reply_auth_id = $baseReply->auth_id;
        $reply->reply_id = $baseReply->id;
        $reply->comment_id = $baseReply->comment_id;
        if ($reply->save()){
            $message = new Message();
            $message->auth_id = $reply->auth_id;
            $message->receive_id = $reply->reply_auth_id;
            $message->content = $reply->content;
            $message->reply_id = $baseReply->id;
            $message->comment_id = $reply->comment_id;
            $message->moment_id = $moment->id;
            $message->save();
            return response()->json([
                'code'=>'200',
                'msg'=>'success'
            ]);
        }
    }
    public function replyLike($id)
    {
        $reply = CommentReply::find($id);
        $reply->like();
        if ($reply->save()){
            return response()->json([
                'code'=>'200',
                'msg'=>'success'
            ]);
        }
    }
    public function getComment($id)
    {
        $baseComment = MomentComment::find($id);
        $baseComment->like = intval($baseComment->like);
        $user = $baseComment->user()->first();
        $baseComment->avatar = $user->avatarUrl;
        $baseComment->userName = $user->nickname;
        $replies = $baseComment->reply()->orderBy('id','DESC')->get();
        $this->formatReplies($replies,$baseComment->auth_id);
        return response()->json([
            'code'=>'200',
            'msg'=>'success',
            'data'=>[
                'comment'=>$baseComment,
                'converse'=>$replies
            ]
        ]);
    }
    public function formatReplies(&$replies,$auth_id)
    {
        $length = count($replies);
        if ($length==0){
            return false;
        }
        for ($i = 0; $i<$length ;$i++){
            $replies[$i]->like = intval($replies[$i]->like);
            $user = OAuthUser::find($replies[$i]->auth_id);
            $replies[$i]->username = $user->nickname;
            $replies[$i]->avatarUrl = $user->avatarUrl;
            if ($replies[$i]->reply_auth_id!=$auth_id){
                $replies[$i]->replyUser = OAuthUser::find($replies[$i]->reply_auth_id)->nickname;
            }

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
        $hotComment = $moment->comments()->orderBy('like','DESC')->limit(3)->get();
        $newsComment = $moment->comments()->orderBy('id','DESC')->limit(10)->get();
        $this->formatComments($hotComment);
        $this->formatComments($newsComment);
        $moment->hotComments = $hotComment;
        $moment->images = $moment->images()->get();
        $moment->videos = $moment->videos()->get();
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
            $comments[$i]->like = intval($comments[$i]->like);
            $user = OAuthUser::find($comments[$i]->auth_id);
            $comments[$i]->avatar = $user->avatarUrl;
            $comments[$i]->userName = $user->nickname;
            $comments[$i]->reply = $comments[$i]->reply()->count();
        }
    }


    public function getComments($id)
    {
        $page = Input::get('page',1);
        $limit = 10;
        $comments = MomentComment::where('moment_id','=',$id)->limit($limit)->offset(($page-1)*$limit)->orderBy('id','DESC')->get();
        $this->formatComments($comments);
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
