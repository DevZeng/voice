<?php

namespace App\Http\Controllers\V2;

use App\Models\MomentVideo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class MomentController extends Controller
{
    //
    public function addMomentVideo()
    {
        $video = new MomentVideo();
        $video->moment_id = Input::get('moment_id');
        $video->base_url = Input::get('base_url');
        $video->url = setUrl(Input::get('base_url'));
        if ($video->save()){
            return response()->json([
                'code'=>'200',
                'msg'=>'success'
            ]);
        }
    }
}
