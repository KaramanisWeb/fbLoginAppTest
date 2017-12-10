<?php

namespace App\Services\Facebook;

use Facebook\Authentication\AccessToken;
use Facebook\Facebook;
use Facebook\FacebookResponse;
use Facebook\Authentication\AccessTokenMetadata;
use Illuminate\Contracts\Session\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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

	/**
	 * Generates and returns the Facebook login URL.
	 * @param string|null $callbackURL
	 * @return string
	 */
	public function getLoginURL(string $callbackURL = null): string
	{
		return $this->helper->getLoginUrl($callbackURL ?: config('services.facebook.callback_url'), $this->permissions);
	}

	/**
	 * Generates and returns the Facebook logout URL.
	 * @param string $token
	 * @param string|null $redirectURL
	 * @return string
	 * @throws \Facebook\Exceptions\FacebookSDKException
	 */
	public function getLogoutURL(string $token, string $redirectURL = null): string
	{
		return $this->helper->getLogoutUrl($token, $redirectURL ?: url('/'));
	}

	/**
	 * Gets and returns the deAuthorized users id.
	 * @return mixed
	 */
	public function getDeAuthID()
	{
		$signedRequest = request()->get('signed_request');
		return $this->parseSignedRequest($signedRequest)['user_id'];
	}

	/**
	 * Gets and returns the logged in user details from facebook.
	 * @return \stdClass
	 * @throws \Facebook\Exceptions\FacebookSDKException
	 */
	public function getUser(): \stdClass
	{
		$token = $this->getToken();
		$response = $this->fb->get('/me?fields=' . implode(',', $this->attributes), $token);
		return $this->prepareUserData($response, $token);
	}

	/**
	 * Validates the users accepted permissions with the ones requested.
	 * @param $accessToken
	 * @return bool
	 */
	public function validatePermissions($accessToken): bool
	{
		$metadata = $this->getMetadata($accessToken);
		$scopes = $metadata->getField('scopes');
		return Helper::array_equal($scopes,$this->permissions);
	}

	/**
	 * Removes the app from the user.
	 * @param $token
	 * @throws \Facebook\Exceptions\FacebookSDKException
	 */
	public function removeApp($token): void
	{
		$this->fb->delete('/me/permissions', [], $token);
	}

	/**
	 * Gets AccessToken Metadata.
	 * @param $accessToken
	 * @return AccessTokenMetadata
	 */
	protected function getMetadata($accessToken): AccessTokenMetadata
	{
		return $this->authClient->debugToken($accessToken);
	}

	/**
	 * Gets the long Lived Access Token.
	 * @return AccessToken|null
	 * @throws \Facebook\Exceptions\FacebookSDKException
	 */
	protected function getToken()
	{
		$token = $this->helper->getAccessToken();
		if (!$token->isLongLived()) {
			$token = $this->authClient->getLongLivedAccessToken($token);
		}
		return $token;
	}

	/**
	 * Prepares the users data to be returned
	 * @param FacebookResponse $response
	 * @param AccessToken $token
	 * @return \stdClass
	 */
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

	/**
	 * Parses the facebooks Signed Request
	 * @param $signed_request
	 * @return mixed|null
	 */
	protected function parseSignedRequest($signed_request)
	{
		list($encoded_sig,$payload) = explode('.', $signed_request ?: '1.1', 2);
		$secret = config('services.facebook.client_secret');

		$sig = Helper::base64UrlDecode($encoded_sig);
		$data = json_decode(Helper::base64UrlDecode($payload), true);

		$expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
		if ($sig !== $expected_sig) {
			Log::error('Bad Signed JSON signature!');
			return null;
		}
		return $data;
	}
}