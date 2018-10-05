<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
class web_login
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
        //return $next($request);
         if(Session::has('web_user_id'))
        {
            return $next($request);
        }else{
             return redirect('signin');
        }
    }
}
