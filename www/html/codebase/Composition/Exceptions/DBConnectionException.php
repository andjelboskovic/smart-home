<?php

namespace SmartHome\Composition\Exceptions;

use Exception;

class DBConnectionException extends Exception
{

	const CONFIG_MISSING = 0x01;
	const CREATE_PDO_FAILED = 0x02;
	const UNKNOWN_ERROR = 0x11;

	private $prefix = "[ DBConnectionException ] ";

	public function __construct(
		$message = "",
		$code = DBConnectionException::UNKNOWN_ERROR,
		\Exception $previous = null
	) {
		$message = $this->prefix . $message;
		parent::__construct($message, $code, $previous);
	}

}
