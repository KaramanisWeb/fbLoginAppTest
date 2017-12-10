<?php

namespace App\Services\Facebook;

use Facebook\Facebook;
use Illuminate\Contracts\Session\Session;

class FacebookManager
{
	protected $fb;
	protected $helper;
	protected $authClient;
	protected $permissions = ['public_profile', 'email'];

	public function __construct(Session $session)
	{
		$this->fb = new Facebook([
			'app_id' => config('services.facebook.client_id'),
			'app_secret' => config('services.facebook.client_secret'),
			'default_graph_version' => 'v2.11',
			'persistent_data_handler' => new PersistentData($session)
		]);

		$this->helper = $this->fb->getRedirectLoginHelper();
		$this->authClient = $this->fb->getOAuth2Client();
	}

	public function getLoginURL(string $callbackURL = null): string
	{
		return $this->helper->getLoginUrl($callbackURL ?: config('services.facebook.callback'), $this->permissions);
	}

	public function getLogoutURL(string $token, string $redirectURL = null): string
	{
		return $this->helper->getLogoutUrl($token, $redirectURL ?: url('/'));
	}

	public function getUser()
	{
		$token = $this->getToken();
		return $this->fb->get('/me', $token);
	}

	public function getToken(){
		$token = $this->helper->getAccessToken();
		if (!$token->isLongLived()) {
			$token = $this->authClient->getLongLivedAccessToken($token);
		}
		return $token;
	}
}