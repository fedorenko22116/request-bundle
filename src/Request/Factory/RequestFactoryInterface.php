<?php

namespace LSBProject\RequestBundle\Request\Factory;

use LSBProject\RequestBundle\Configuration\PropConfigurationInterface;
use LSBProject\RequestBundle\Request\AbstractRequest;
use Symfony\Component\HttpFoundation\Request;

interface RequestFactoryInterface
{
    /**
     * @param class-string<object>       $class
     * @param Request                    $request
     * @param PropConfigurationInterface $configuration
     *
     * @return AbstractRequest
     */
    public function create($class, Request $request, PropConfigurationInterface $configuration = null);
}
