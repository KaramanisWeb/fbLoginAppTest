<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ActiveUser
{
	/**
	 * Checks if the authenticated user is active if not he gets auto logged out.
	 * @param $request
	 * @param Closure $next
	 * @param null $guard
	 * @return \Illuminate\Http\RedirectResponse|mixed
	 */
	public function handle($request, Closure $next, $guard = null)
	{
		if (Auth::user()->is_active === false) {
			Auth::logout();
			return redirect()->route('home');
		}

		return $next($request);
	}
}
