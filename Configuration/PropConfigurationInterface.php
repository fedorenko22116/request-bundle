<?php

namespace LSBProject\RequestBundle\Configuration;

interface PropConfigurationInterface
{
    /**
     * @param string $name
     *
     * @return void
     */
    public function setName($name);

    /**
     * @param string|null $type
     *
     * @return void
     */
    public function setType($type);

    /**
     * @param mixed[] $options
     *
     * @return void
     */
    public function setOptions(array $options);

    /**
     * @param string $converter
     *
     * @return void
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
