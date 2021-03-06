<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MomentComment extends Model
{
    //
    public function like()
    {
        $this->like += 1;
    }
    public function user()
    {
        return $this->belongsTo('App\Models\OAuthUser','auth_id','id');
    }
    public function reply()
    {
        return $this->hasMany('App\Models\CommentReply','comment_id','id');
    }
}
