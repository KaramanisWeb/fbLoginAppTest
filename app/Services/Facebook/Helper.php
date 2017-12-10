<?php

namespace App\Services\Facebook;


class Helper
{
	public static function array_equal($a, $b): bool
	{
		return (is_array($a) && is_array($b) && count($a) === count($b) && array_diff($a, $b) === array_diff($b, $a));
	}

	public static function base64UrlDecode($input)
	{
		return base64_decode(strtr($input, '-_', '+/'));
	}
}