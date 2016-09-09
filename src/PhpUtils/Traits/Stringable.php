<?php

namespace PhpUtils\Traits;

trait Stringable 
{
	abstract protected function toString();

	public function __toString()
	{
		try {
			return $this->toString();
		} catch (\Exception $e) {
			return get_class($this);
		}
	}
}
