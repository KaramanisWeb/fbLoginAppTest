<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
	public function showHome()
	{
		return view('home');
	}

	public function showUserPage()
	{
		return view('user');
	}
}
