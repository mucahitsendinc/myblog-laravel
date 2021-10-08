<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CachePosts
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

        if(Cache::get('posts')==null){
            Cache::remember('posts', 3600, function () use($next,$request) {
                return $next($request);
            });
        }else{
            return Cache::get('posts');
        }
        return $next($request);
    }
}
