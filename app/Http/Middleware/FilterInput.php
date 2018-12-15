<?php

namespace App\Http\Middleware;

use Closure;

class FilterInput
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
        $input = $request->all();
        if ($input) {
            array_walk_recursive($input, function (&$item, $key) {
                if($item)
                $item = filter_var($item, FILTER_SANITIZE_STRING,FILTER_FLAG_STRIP_HIGH);
            });
            $request->merge($input);
        }

        return $next($request);
    }
}