<?php

namespace LSBProject\RequestBundle\Configuration;

/**
 * @Annotation
 */
class PropConverter extends AbstractPropConfiguration
{
    /**
     * @var string|null
     */
    private $type;

    /**
     * @var mixed[]
     */
    private $options = [];

    /**
     * @var string|null
     */
    private $converter;

    /**
     * @var string
     */
    private $name;

    /**
     * @param string $value
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
}
