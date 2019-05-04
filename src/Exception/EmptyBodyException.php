<?php

namespace App\Exception;

use Throwable;

class EmptyBodyException extends \Exception
{
	public function __construct
	(
		string $message = "",
		int $code = 0,
		Throwable $previous = null
	) {
		parent::__construct( "cannot be empty body for POST/PUT operations", $code, $previous );
	}
}