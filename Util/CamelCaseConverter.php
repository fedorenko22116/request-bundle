<?php

namespace LSBProject\RequestBundle\Util;

class CamelCaseConverter implements CamelCaseConverterInterface
{
    /**
     * {@inheritDoc}
     */
    public function convert($value)
    {
        return strtolower(preg_replace('/[A-Z]/', '_\\0', lcfirst($value)));
    }
}
