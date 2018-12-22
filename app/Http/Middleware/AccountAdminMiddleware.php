<?php
namespace App\Http\Middleware;
use Auth;
use Closure;

class AccountAdminMiddleware{

	/**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
	public function handle($request, Closure $next)
	{
		if( Auth::user()->role_id != 4 )
		{
			abort(404);
		}
		return $next($request);
	}

}
