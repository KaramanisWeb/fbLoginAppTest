<?php

namespace App\Services\Facebook;

use Facebook\Authentication\AccessToken;
use Facebook\Facebook;
use Facebook\FacebookResponse;
use Illuminate\Contracts\Session\Session;
use Carbon\Carbon;

class FacebookManager
{
	protected $fb;
	protected $helper;
	protected $authClient;
	protected $permissions = ['public_profile', 'email'];
	protected $attributes = ['id', 'name', 'email', 'gender', 'verified', 'link'];

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
		return $this->helper->getLoginUrl($callbackURL ?: config('services.facebook.callback_url'), $this->permissions);
	}

	public function getLogoutURL(string $token, string $redirectURL = null): string
	{
		return $this->helper->getLogoutUrl($token, $redirectURL ?: url('/'));
	}

	public function getUser(): \stdClass
	{
		$token = $this->getToken();
		$response = $this->fb->get('/me?fields=' . implode(',', $this->attributes), $token);
		return $this->prepareUserData($response, $token);
	}

	public function validatePermissions($accessToken): bool
	{
		$metadata = $this->getMetadata($accessToken);
		$scopes = $metadata->getField('scopes');
		return Helper::array_equal($scopes,$this->permissions);
	}

	public function removeApp($token)
	{
		$this->fb->delete('/me/permissions', [], $token);
	}

	protected function getMetadata($accessToken)
	{
		return $this->authClient->debugToken($accessToken);
	}

	protected function getToken()
	{
		$token = $this->helper->getAccessToken();
		if (!$token->isLongLived()) {
			$token = $this->authClient->getLongLivedAccessToken($token);
		}
		return $token;
	}

	protected function prepareUserData(FacebookResponse $response, AccessToken $token): \stdClass
	{
		$data = $response->getDecodedBody();
		$user = [
			'token' => $token->getValue(),
			'expires' => Carbon::createFromTimestamp($token->getExpiresAt()->getTimestamp()),
			'picture' => isset($data['id']) ? 'https://graph.facebook.com/v2.11/' . $data['id'] . '/picture?type=normal' : null,
		];
		return (object)array_merge($user, $data);
	}
}