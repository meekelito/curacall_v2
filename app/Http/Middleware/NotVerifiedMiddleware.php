<?php namespace App\Http\Middleware;
use Auth;
use Closure;

class NotVerifiedMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->is_verified == 0 && !$request->is('user/newpassword') && !$request->is('user/verify'))
        {
           return redirect()->route('user.newpassword');
        }

        return $next($request);
    }

}