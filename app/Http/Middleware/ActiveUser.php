<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ActiveUser
{
	public function handle($request, Closure $next, $guard = null)
	{
		if (Auth::user()->is_active === false) {
			Auth::logout();
			return redirect()->route('home');
		}

		return $next($request);
	}
}
