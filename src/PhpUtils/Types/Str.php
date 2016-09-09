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

    public function toString()
    {
        return $this->value;
    }

    public function contains($needle)
    {
        return strpos($this->value, $needle) !== false;
    }

    public function firstOccurrenceOf($needle)
    {
        return strpos($this->value, $needle);
    }

    public function rawUrlDecode()
    {
        return new static(rawurldecode($this->value));
    }

    public function replace($search, $replace)
    {
        $this->value = str_replace($search, $replace, $this->value);

        return $this;
    }

    public function truncate($position)
    {
        return new static(substr($this->value, 0, $position));
    }

    public function truncateOnChar($char)
    {
        if ($this->contains($char)) {
            return new static(
                $this->truncate(
                    $this->firstOccurrenceOf($char)
                )
            );
        }
        return new static($this->value);
    }
}
