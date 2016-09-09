<?php

namespace PhpUtils\Types;

use PhpUtils\Exceptions\InvalidTypeException;
use PhpUtils\Traits\Stringable;

class Str
{
	use Stringable;

	private $value;

	function __construct($value = '')
	{
		$isArray = is_array($value);
		$isStringableObject = is_object($value) && method_exists($value, '__toString');
		$isStringableVar = !is_object($value) && settype($value, 'string') !== false;

		if (!$isArray && ($isStringableObject || $isStringableVar)) {
		    $this->value = (string) $value;
		} else {
			throw new InvalidTypeException('Not a stringable value');
		}
	}

	public static function make($value = '')
	{
		return new static($value);
	}

	private function toString()
	{
		return $this->value;
	}

	public function replace($search, $replace) 
	{
		$this->value = str_replace($search, $replace, $this->value);

		return $this;
	}
}
