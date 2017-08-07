<?php

namespace App\Http\Middleware;

use Closure;

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
                'code'=>'401',
                'msg'=>'Unauthorized API Request'
            ]);
        }
        return $next($request);
    }
}
