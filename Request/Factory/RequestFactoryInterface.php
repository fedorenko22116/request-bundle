<?php

namespace LSBProject\RequestBundle\Request\Factory;

use LSBProject\RequestBundle\Request\AbstractRequest;

interface RequestFactoryInterface
{
    /**
     * @param class-string<AbstractRequest> $class
     * @return AbstractRequest
     */
    public function create($class);
}
