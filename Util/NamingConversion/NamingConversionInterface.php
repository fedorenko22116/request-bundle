<?php

namespace LSBProject\RequestBundle\Util\NamingConversion;

interface NamingConversionInterface
{
    /**
     * @param string $value
     * @return string
     */
    public function convert($value);
}
