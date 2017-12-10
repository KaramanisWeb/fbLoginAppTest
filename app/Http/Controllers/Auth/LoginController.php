<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Facebook\FacebookManager;
use Illuminate\Http\Request;
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

		if(!$this->facebook->validatePermissions($fbUser->token)){
			session()->flash('status','Some permissions are missing! Please try again!');
			$this->facebook->removeApp($fbUser->token);
			return redirect()->route('home');
		}

		$user = User::query()->where('fb_uid', '=', $fbUser->id)->first();

		if (!$user) {
			$user = User::query()->create([
				'name' => $fbUser->name,
				'email' => $fbUser->email,
				'fb_token' => $fbUser->token,
				'fb_uid' => $fbUser->id,
				'picture' => $fbUser->picture,
				'link' => $fbUser->link,
				'fb_token_expires' => $fbUser->expires,
				'is_active' => true,
			]);
		}else{
			$user->update([
				'name' => $fbUser->name,
				'email' => $fbUser->email,
				'fb_token' => $fbUser->token,
				'fb_token_expires' => $fbUser->expires,
				'is_active' => true,
			]);
		}

		Auth::login($user, true);

		return redirect()->route('user');
	}

	public function handleDeAuthCallback(){

	}

	public function logout(Request $request)
	{
		$logoutUrl = $this->facebook->getLogoutURL(Auth::user()->fb_token);
		Auth::logout();
		if ($request->fromFacebook === 'on') {
			return redirect()->away($logoutUrl);
		}
		return redirect()->route('home');
	}
}
