<?php

namespace App\Http\Controllers;

use App\Models\Advert;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class WarehouseController extends Controller
{
    //
    public function getAdverts()
    {
        $warehouse = Warehouse::where('app_id','=',Input::get('app_id'))->first();
        $adverts = Advert::where('warehouse_id','=',$warehouse->id)->get();
        return response()->json([
            'code'=>'200',
            'msg'=>'success',
            'data'=>$adverts
        ]);
    }
}
