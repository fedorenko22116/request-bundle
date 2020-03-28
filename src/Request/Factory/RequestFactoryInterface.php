<?php

namespace LSBProject\RequestBundle\Request\Factory;

use LSBProject\RequestBundle\Request\AbstractRequest;
use Symfony\Component\HttpFoundation\Request;

interface RequestFactoryInterface
{
    /**
     * @param class-string<AbstractRequest> $class
     * @param Request                       $request
     *
     * @return AbstractRequest
     */
    public function create($class, Request $request);
}
