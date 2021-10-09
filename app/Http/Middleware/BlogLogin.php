<?php

namespace App\Http\Middleware;

use App\Http\Controllers\ApiController;
use Closure;
use Illuminate\Http\Request;

class BlogLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $check=new ApiController;
        $token=$check->checkToken($request->bearerToken());
        if($token){
            return $next($request);
        }
        return response()->json(['success'=>false,'message'=>'Oturum süresi aşılmış'],401);

       //if($request)

    }
}
