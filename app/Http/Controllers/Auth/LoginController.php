<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Facebook\FacebookManager;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
	protected $facebook;

	public function __construct(FacebookManager $facebook)
	{
		$this->facebook = $facebook;
	}

	public function redirectToFb()
	{
		$fbURL = $this->facebook->getLoginURL();
		return redirect()->away($fbURL);
	}

	public function handleLoginCallback(){
		$fbUser = $this->facebook->getUser();
	}

	public function handleDeAuthCallback(){

	}

	public function logout()
	{
		Auth::logout();
		return redirect()->route('home');
	}
}
