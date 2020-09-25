<?php

namespace LSBProject\RequestBundle\Util\ReflectionExtractor\DTO;

class Type
{
    /**
     * @var string[]
     */
    private $values;

    /**
     * @var bool
     */
    private $nullable = false;

    /**
     * @return string[]
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param string[] $values
     *
     * @return self
     */
    public function setValues($values)
    {
        $this->values = $values;

        return $this;
    }

    /**
     * @param string $value
     *
     * @return self
     */
    public function addValue($value)
    {
        $this->values[] = $value;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirst()
    {
        return isset($this->values[0]) ? $this->values[0] : null;
    }

    /**
     * @return bool
     */
    public function isNullable()
    {
        return $this->nullable;
    }

    /**
     * @param bool $nullable
     *
     * @return self
     */
    public function setNullable($nullable)
    {
        $this->nullable = $nullable;

        return $this;
    }
}
