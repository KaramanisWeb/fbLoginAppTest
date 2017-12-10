<?php

namespace App\Services\Facebook;

use Facebook\Facebook;

class FacebookManager
{
	protected $fb;

	public function __construct()
	{
		$this->fb = new Facebook([
			'app_id' => config('services.facebook.client_id'),
			'app_secret' => config('services.facebook.client_secret'),
			'default_graph_version' => 'v2.11',
		]);
	}

	public function getLoginURL()
	{

	}

	public function getLogoutURL()
	{

	}

	public function getUser()
	{

	}

	public function getToken(){

	}
}