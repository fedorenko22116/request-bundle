<?php

namespace LSBProject\RequestBundle\Request\Factory;

use LSBProject\RequestBundle\Configuration\PropConfigurationInterface;
use LSBProject\RequestBundle\Configuration\RequestStorage;
use LSBProject\RequestBundle\Request\AbstractRequest;
use Symfony\Component\HttpFoundation\Request;

interface RequestFactoryInterface
{
    /**
     * @param class-string<AbstractRequest>   $class
     * @param Request                         $request
     * @param PropConfigurationInterface|null $configuration
     * @param RequestStorage|null             $requestStorage
     *
     * @return AbstractRequest
     */
    public function create(
        $class,
        Request $request,
        PropConfigurationInterface $configuration = null,
        RequestStorage $requestStorage = null
    );
}
