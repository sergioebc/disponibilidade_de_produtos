<?php

namespace App\Utils;

class ApiError
{
	public static function errorMessage($message, $code)
	{
		return [
			'data' => [
				'msg' => $message,
				'code' => $code
			]
		];
	}
}
