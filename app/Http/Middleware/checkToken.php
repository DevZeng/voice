<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

class checkToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $code = $request->get('_token');
        if (empty($code)){
            return response()->json([
                'code'=>'400',
                'msg'=>'Param ERROR'
            ]);
        }
        $user_id = getUserId($request->get('_token'));
        if ($user_id){
            return $next($request);
        }else{
            return response()->json([
                'code'=>'401',
                'msg'=>'Unauthorized API Request'
            ]);
        }

    }
}
