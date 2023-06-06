<?php

namespace LSBProject\RequestBundle\Configuration;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationAnnotation;

/**
 * @Annotation
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Discriminator
{
    /**
     * Discriminator field name
     *
     * @var string
     */
    public $field;

    /**
     * @var array<string, PropConverter|class-string>
     */
    public $mapping;

    /**
     * @param string|array<string, mixed>               $field
     * @param array<string, PropConverter|class-string> $mapping
     */
    public function __construct($field, array $mapping)
    {
        $values = [];

        if (\is_string($field)) {
            $values['value'] = $field;
        } else {
            $values = $field;
        }

        $values['mapping'] = isset($values['mapping']) ? $values['mapping'] : $mapping;

//        parent::__construct($values);
    }

    /**
     * @param string $value
     *
     * @return void
     */
    public function setValue($value)
    {
        $this->field = $value;
    }

    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param string $field
     *
     * @return void
     */
    public function setField($field)
    {
        $this->field = $field;
    }

    /**
     * @return array<string, PropConverter|class-string>
     */
    public function getMapping()
    {
        return $this->mapping;
    }

    /**
     * @param array<string, PropConverter|class-string> $mapping
     *
     * @return void
     */
    public function setMapping($mapping)
    {
        $this->mapping = $mapping;
    }

    /**
     * {@inheritDoc}
     */
    public function getAliasName()
    {
        return 'discriminator';
    }

    /**
     * {@inheritDoc}
     */
    public function allowArray()
    {
        return true;
    }
}
