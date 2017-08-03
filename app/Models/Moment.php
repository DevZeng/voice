<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Moment extends Model
{
    //
    public function images()
    {
        return $this->hasMany('App\Models\MomentImage','moment_id','id');
    }
    public function comments()
    {
        return $this->hasMany('App\Models\MomentComment','moment_id','id');
    }
    public function likes()
    {
        return $this->hasMany('App\Models\MomentLike','moment_id','id');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\OAuthUser','auth_id','id');
    }
    public function collects()
    {
        return $this->hasMany('App\Models\MomentCollect','moment_id','id');
    }
}
