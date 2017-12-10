<?php

namespace App\Services\Facebook;

use Facebook\PersistentData\PersistentDataInterface;
use Illuminate\Contracts\Session\Session;

class PersistentData implements PersistentDataInterface
{
	protected $session;

	public function __construct(Session $session)
	{
		$this->session = $session;
	}

	public function get($key)
	{
		return $this->session->get('FB_' . $key);
	}

	public function set($key, $value)
	{
		$this->session->put('FB_' . $key, $value);
	}
}