<?php

namespace LSBProject\RequestBundle\Configuration;

abstract class AbstractPropConfiguration implements PropConfigurationInterface
{
    const ALIAS = '_lsbproject_property';
    const BUILTIN_TYPES = [null, "string", "int", "float", "bool", "array"];

    /**
     * {@inheritDoc}
     */
    public function isBuiltInType()
    {
        return in_array($this->getType(), self::BUILTIN_TYPES, true);
    }

    /**
     * {@inheritDoc}
     */
    public function getAliasName()
    {
        return ltrim(self::ALIAS, '_');
    }

    /**
     * {@inheritDoc}
     */
    public function allowArray()
    {
        return false;
    }
}
