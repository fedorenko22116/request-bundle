<?php

declare(strict_types=1);

namespace LSBProject\RequestBundle\Serializer;

use LSBProject\RequestBundle\Configuration\RequestStorage;
use LSBProject\RequestBundle\Contract\RequestInterface;
use Symfony\Component\HttpFoundation\Request;

interface RequestFactoryInterface
{
    /**
     * @param class-string<RequestInterface> $class
     */
    public function create(string $class, Request $request, RequestStorage $requestStorage = null): object;
}
