<?php

declare(strict_types=1);

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
     * @param bool $isDto
     *
     * @return void
     */
    public function setIsDto($isDto);

    /**
     * @param bool $isOptional
     *
     * @return void
     */
    public function setIsOptional($isOptional);

    /**
     * @param bool $isCollection
     *
     * @return void
     */
    public function setIsCollection($isCollection);

    /**
     * @return string|null
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

    /**
     * @return bool
     */
    public function isDto();

    /**
     * @return bool
     */
    public function isCollection();

    /**
     * @return bool
     */
    public function isOptional();
}
