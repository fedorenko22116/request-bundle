<?php

namespace LSBProject\RequestBundle\Configuration;

/**
 * @Annotation
 */
class Entity extends PropConverter
{
    /**
     * @var string
     */
    private $expr;

    /**
     * @return string
     */
    public function getExpr()
    {
        return $this->expr;
    }

    /**
     * @param string $expr
     */
    public function setExpr(string $expr)
    {
        $this->expr = $expr;
    }
}
