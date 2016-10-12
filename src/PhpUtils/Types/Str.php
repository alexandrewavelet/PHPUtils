<?php

namespace PhpUtils\Types;

use PhpUtils\Exceptions\InvalidTypeException;
use PhpUtils\Traits\Stringable;

class Str
{
	use Stringable;

	private $value;

    const TRIM_BOTH = 0;
    const TRIM_START = 1;
    const TRIM_END = 2;

    const TRUNCATE_ON_FIRST = 0;
    const TRUNCATE_ON_LAST = 1;

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

	public function toString()
	{
		return $this->value;
	}

	public function contains($needle)
    {
        return strpos($this->value, $needle) !== false;
    }

    public function endsWith($needle)
    {
        if (substr($this->value, - strlen($needle)) === (string) $needle) {
            return true;
        }

        return false;
    }

    public function explode($delimiter)
    {
        return explode($delimiter, $this->value);
    }

    public function firstOccurrenceOf($needle)
    {
        return strpos($this->value, $needle);
    }

    public function lastOccurrenceOf($needle)
    {
        return strrpos($this->value, $needle);
    }

    public function rawUrlDecode()
    {
        return new static(rawurldecode($this->value));
    }

	public function replace($search, $replace)
	{
		$this->value = str_replace($search, $replace, $this->value);

		return new static($this->value);
	}

	public function toLowercase()
    {
        return new static(strtolower($this->value));
    }

	public function trim($chars = null, $mode = Str::TRIM_BOTH)
    {
        switch ($mode) {
            case Str::TRIM_BOTH :
                $function = 'trim';
                break;

            case Str::TRIM_START :
                $function = 'ltrim';
                break;

            case Str::TRIM_END :
                $function = 'rtrim';
                break;

            default :
                throw new InvalidTypeException('Invalid mode type');
        }

        $chars = ($chars) ?: '\t\n\r\0\x0B';

        return new static($function($this->value, $chars));
    }

	public function truncate($position)
    {
        return new static(substr($this->value, 0, $position));
    }

    public function truncateOnChar($char, $mode = Str::TRUNCATE_ON_FIRST)
    {
        if ($this->contains($char)) {
            switch ($mode) {
                case Str::TRUNCATE_ON_FIRST :
                    $position = $this->firstOccurrenceOf($char);
                    break;

                case Str::TRUNCATE_ON_LAST :
                    $position = $this->lastOccurrenceOf($char);
                    break;

                default :
                    throw new InvalidTypeException('Invalid mode type');
            }

            return new static(
                $this->truncate($position)
            );
        }
        return new static($this->value);
    }
}
