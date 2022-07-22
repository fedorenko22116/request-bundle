<?php

namespace LSBProject\RequestBundle\Configuration;

/**
 * @Annotation
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
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
     * If property is an array of objects
     *
     * @var bool
     */
    private $isCollection = false;

    /**
     * If type is optional
     *
     * @var bool
     */
    private $isOptional = true;

    /**
     * @param string|array<string, mixed> $data
     * @param string|null                 $name
     * @param array<string, mixed>        $options
     * @param bool                        $isOptional
     * @param string|null                 $converter
     * @param bool                        $isCollection
     * @param bool                        $isDto
     */
    public function __construct(
        $data = [],
        $name = null,
        $options = [],
        $isOptional = false,
        $converter = null,
        $isCollection = false,
        $isDto = false
    ) {
        $values = [];

        if (\is_string($data)) {
            $values['value'] = $data;
        } else {
            $values = $data;
        }

        $values['name'] = isset($values['name']) ? $values['name'] : $name;
        $values['options'] = isset($values['options']) ? $values['options'] : $options;
        $values['isOptional'] = isset($values['isOptional']) ? $values['isOptional'] : $isOptional;
        $values['converter'] = isset($values['converter']) ? $values['converter'] : $converter;
        $values['isCollection'] = isset($values['isCollection']) ? $values['isCollection'] : $isCollection;
        $values['isDto'] = isset($values['isDto']) ? $values['isDto'] : $isDto;

        parent::__construct($values);
    }

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
    public function isCollection()
    {
        return $this->isCollection;
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

    /**
     * {@inheritDoc}
     */
    public function setIsCollection($isCollection)
    {
        $this->isCollection = $isCollection;
    }
}
