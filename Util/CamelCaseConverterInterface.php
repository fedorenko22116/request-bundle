<?php

namespace LSBProject\RequestBundle\Util;

interface CamelCaseConverterInterface
{
    /**
     * @param string $value
     * @return string
     */
    public function convert(string $value);
}
