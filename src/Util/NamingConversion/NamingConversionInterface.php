<?php

namespace LSBProject\RequestBundle\Util\NamingConversion;

interface NamingConversionInterface
{
    /**
     * @param string $value
     *
     * @return string
     */
    public function normalize($value);

    /**
     * @param string $value
     *
     * @return string
     */
    public function denormalize($value);
}
