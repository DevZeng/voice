<?php

namespace App\Http\Middleware;

use App\Models\OAuthUser;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUser
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
        $user_id = getUserId($request->get('_token'));
        if ($user_id){
            $user = OAuthUser::find($user_id);
            if ($user->ban==1){
                return response()->json([
                    'code'=>'401',
                    'msg'=>'账户已被封禁！'
                ]);
            }
            return $next($request);
        }
        return response()->json([
            'code'=>'401',
            'msg'=>'未登录'
        ]);
    }
}
