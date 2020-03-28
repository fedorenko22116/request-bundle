<?php

namespace LSBProject\RequestBundle\Configuration;

/**
 * @Annotation
 */
class PropConverter extends AbstractPropConfiguration
{
    /**
     * Property type to be used for converters
     *
     * @var string|null
     */
    private $type;

    /**
     * Mapping to ParamConverter::options
     *
     * @var mixed[]
     */
    private $options = [];

    /**
     * Mapping to ParamConverter::converter
     *
     * @var string|null
     */
    private $converter;

    /**
     * By default snake_case naming conversion is used
     * for property name. This property can be used to
     * point exact name for parameter from request
     *
     * @var string
     */
    private $name;

    /**
     * If object should be converted as request
     *
     * @var bool
     */
    private $isDto = false;

    /**
     * If type is optional
     *
     * @var bool
     */
    private $isOptional = true;

    /**
     * @param string $value
     *
     * @return void
     */
    public function setValue($value)
    {
        $this->type = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritDoc}
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * {@inheritDoc}
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * {@inheritDoc}
     */
    public function setConverter($converter)
    {
        $this->converter = $converter;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritDoc}
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * {@inheritDoc}
     */
    public function getConverter()
    {
        return $this->converter;
    }

    /**
     * {@inheritDoc}
     */
    public function setIsDto($isDto)
    {
        $this->isDto = $isDto;
    }

    /**
     * {@inheritDoc}
     */
    public function isDto()
    {
        return $this->isDto;
    }

    /**
     * {@inheritDoc}
     */
    public function setIsOptional($isOptional)
    {
        $this->isOptional = $isOptional;
    }

    /**
     * {@inheritDoc}
     */
    public function isOptional()
    {
        return $this->isOptional;
    }
}
