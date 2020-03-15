<?php

namespace LSBProject\RequestBundle\Configuration;

interface PropConfigurationInterface
{
    /**
     * @param string $name
     */
    public function setName($name);

    /**
     * @param string|null $type
     */
    public function setType($type);

    /**
     * @param array $options
     */
    public function setOptions(array $options);

    /**
     * @param string $converter
     */
    public function setConverter($converter);

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string|null
     */
    public function getType();

    /**
     * @return bool
     */
    public function isBuiltInType();

    /**
     * @return mixed[]
     */
    public function getOptions();

    /**
     * @return string|null
     */
    public function getConverter();
}
