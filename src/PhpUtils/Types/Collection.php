<?php

namespace PhpUtils\Types;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use PhpUtils\Exceptions\InvalidTypeException;
use PhpUtils\Traits\Stringable;

class Collection implements ArrayAccess, Countable, IteratorAggregate, JsonSerializable
{
    use Stringable;

    private $items;

    function __construct($items = [])
    {
        if (is_array($items)) {
            $this->items = $items;
        } else {
            throw new InvalidTypeException('Not an array');
        }
    }

    public static function make($items = [])
    {
        return new static($items);
    }

    public function filter(callable $callback = null)
    {
        if ($callback) {
            return new static(array_filter($this->items, $callback,  ARRAY_FILTER_USE_BOTH));
        }
        return new static(array_filter($this->items));
    }

    public function join($glue = ',')
    {
        return implode($glue, $this->items);
    }

    public function map(callable $callback)
    {
        $keys = array_keys($this->items);
        $items = array_map($callback, $this->items, $keys);

        return new static(array_combine($keys, $items));
    }

    public function toString()
    {
        return $this->count().' elements';
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->items);
    }

    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->items[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    public function count()
    {
        return count($this->items);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    function jsonSerialize()
    {
        return array_map(function ($value) {
            if ($value instanceof JsonSerializable) {
                return $value->jsonSerialize();
            } else {
                return $value;
            }
        }, $this->items);
    }
}
