<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Facebook\FacebookManager;

class LoginController extends Controller
{
	protected $facebook;

	public function __construct(FacebookManager $facebook)
	{
		$this->facebook = $facebook;
	}

	public function redirectToFb()
	{

	}

	public function handleCallback(){

	}

	public function handleDeAuthCallback(){

	}

	public function logout()
	{

	}
}
