<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticated
{
	/**
	 * Checks if the user is authenticated.
	 * @param $request
	 * @param Closure $next
	 * @param null $guard
	 * @return \Illuminate\Http\RedirectResponse|mixed
	 */
	public function handle($request, Closure $next, $guard = null)
	{
		if (!Auth::guard($guard)->check()) {
			return redirect()->route('home');
		}

		return $next($request);
	}
}
