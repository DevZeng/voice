<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    //
    public function moments()
    {
        return $this->hasMany('App\Models\Moment','warehouse_id','id');
    }
    public function adverts()
    {
        return $this->hasMany('App\Models\Advert','warehouse_id','id');
    }
}
