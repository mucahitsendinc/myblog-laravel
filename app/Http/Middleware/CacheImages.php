<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CacheImages
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
        //Cache::delete('images');
        if(!Cache::has('images')){
            Cache::remember('images', 3600, function () use($next,$request) {
                return $next($request);
            });
        }else{
            return Cache::get('images');
        }
        return $next($request);
    }
}
