<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OAuthUser extends Model
{
    //
    public function moments()
    {
        return $this->hasMany('App\Models\Moment','auth_id','id');
    }
    public function comments()
    {
        return $this->hasMany('App\Models\MomentComment','auth_id','id');
    }
    public function collects()
    {
        return $this->hasMany('App\Models\MomentCollect','auth_id','id');
    }
}
