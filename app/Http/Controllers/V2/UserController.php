<?php

namespace App\Http\Controllers\V2;

use App\Models\CommentReply;
use App\Models\Message;
use App\Models\Moment;
use App\Models\MomentComment;
use App\Models\OAuthUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class UserController extends Controller
{
    //
    public function readCommentNotify($id)
    {
        $message = Message::find($id);
        $message->read();
        if ($message->save()){
            return response()->json([
                'code'=>'200',
                'msg'=>'success'
            ]);
        }
    }
    public function getCommentNotifies()
    {
        $limit = 10;
        $page = Input::get('page',1);
        $auth_id = getUserId(Input::get('_token'));
        $messages = Message::where('receive_id','=',$auth_id)->limit($limit)->offset(($page-1)*$limit)->orderBy('id','DESC')->get();
        $this->formatMessages($messages);
        return response()->json([
            'code'=>'200',
            'msg'=>'success',
            'data'=>$messages
        ]);
    }
    public function formatMessages(&$messages)
    {
        $length = count($messages);
        if ($length==0){
            return false;
        }
        for ($i=0;$i<$length;$i++){
            $messages[$i]->auth = OAuthUser::find($messages[$i]->auth_id)->nickname;
            $messages[$i]->topic = Moment::find($messages[$i]->moment_id)->content;
            $messages[$i]->auth_avatar = OAuthUser::find($messages[$i]->auth_id)->avatarUrl;
            if ($messages[$i]->comment_id !=0){
                if ($messages[$i]->reply_id != 0){
                    $messages[$i]->comment = CommentReply::find($messages[$i]->reply_id)->content;
                }else{
                    $messages[$i]->comment = MomentComment::find($messages[$i]->comment_id)->content;
                }
            }
        }
    }
    public function getUnReadCount()
    {
        $auth_id = getUserId(Input::get('_token'));
        $count = Message::where('receive_id','=',$auth_id)->where('read','=','0')->count();
        return response()->json([
            'code'=>'200',
            'msg'=>'success',
            'data'=>$count
        ]);
    }
}
