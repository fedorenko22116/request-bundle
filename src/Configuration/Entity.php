<?php

namespace LSBProject\RequestBundle\Configuration;

/**
 * @Annotation
 */
#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_PROPERTY)]
final class Entity extends PropConverter
{
    /**
     * @var string
     */
    private $expr;

    /**
     * @var array<string, string>
     */
    private $mapping;

    /**
     * @param string|array<string, mixed> $data
     * @param string|null                 $name
     * @param string|null                 $expr
     * @param array<string, mixed>        $options
     * @param bool                        $isOptional
     * @param string|null                 $converter
     * @param bool                        $isCollection
     * @param bool                        $isDto
     * @param array<string, string>       $mapping
     */
    public function __construct(
        $data = [],
        $name = null,
        $expr = null,
        $options = [],
        $isOptional = false,
        $converter = null,
        $isCollection = false,
        $isDto = false,
        $mapping = []
    ) {
        $values = [];

        if (\is_string($data)) {
            $values['value'] = $data;
        } else {
            $values = $data;
        }

        $values['expr'] = isset($values['expr']) ? $values['expr'] : $expr;
        $values['mapping'] = isset($values['mapping']) ? $values['mapping'] : $mapping;

        parent::__construct($values, $name, $options, $isOptional, $converter, $isCollection, $isDto);
    }

    /**
     * @return string
     */
    public function getExpr()
    {
        return $this->expr;
    }

    /**
     * @param string $expr
     *
     * @return void
     */
    public function setExpr($expr)
    {
        $this->expr = $expr;
    }

    /**
     * @return array<string, string>
     */
    public function getMapping()
    {
        return $this->mapping;
    }

    /**
     * @param array<string, string> $mapping
     *
     * @return void
     */
    public function setMapping($mapping)
    {
        $this->mapping = $mapping;
    }
}
