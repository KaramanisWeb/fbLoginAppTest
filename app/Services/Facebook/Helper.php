<?php

namespace App\Services\Facebook;


class Helper
{
	/**
	 * Compares 2 arrays
	 * @param $a
	 * @param $b
	 * @return bool
	 */
	public static function array_equal($a, $b): bool
	{
		return (is_array($a) && is_array($b) && count($a) === count($b) && array_diff($a, $b) === array_diff($b, $a));
	}

	/**
	 * Base64 Url Decoding
	 * @param $input
	 * @return bool|string
	 */
	public static function base64UrlDecode($input)
	{
		return base64_decode(strtr($input, '-_', '+/'));
	}
}