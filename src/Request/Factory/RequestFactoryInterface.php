<?php

namespace LSBProject\RequestBundle\Request\Factory;

use LSBProject\RequestBundle\Configuration\RequestStorage;
use LSBProject\RequestBundle\Request\RequestInterface;
use Symfony\Component\HttpFoundation\Request;

interface RequestFactoryInterface
{
    /**
     * @param class-string<RequestInterface> $class
     * @param Request                        $request
     * @param RequestStorage|null            $requestStorage
     *
     * @return RequestInterface
     */
    public function create($class, Request $request, RequestStorage $requestStorage = null);
}
