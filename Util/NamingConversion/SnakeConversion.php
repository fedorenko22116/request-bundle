<?php

namespace LSBProject\RequestBundle\Util\NamingConversion;

class SnakeConversion implements NamingConversionInterface
{
    /**
     * {@inheritDoc}
     */
    public function convert($value)
    {
        return strtolower(preg_replace('/[A-Z]/', '_\\0', lcfirst($value)));
    }
}
