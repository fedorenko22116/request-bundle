<?php

namespace LSBProject\RequestBundle\Util\NamingConversion;

final class CamelCaseToSnakeConversion implements NamingConversionInterface
{
    /**
     * {@inheritDoc}
     */
    public function normalize($value)
    {
        return strtolower(preg_replace('/[A-Z]/', '_\\0', lcfirst($value)) ?: '');
    }

    /**
     * {@inheritDoc}
     */
    public function denormalize($value)
    {
        $camelCasedName = preg_replace_callback('/(^|_|\.)+(.)/', function ($match) {
            return ('.' === $match[1] ? '_' : '') . strtoupper($match[2]);
        }, $value);

        return lcfirst($camelCasedName);
    }
}
