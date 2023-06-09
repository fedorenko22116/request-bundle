<?php

declare(strict_types=1);

namespace LSBProject\RequestBundle\Mapping;

use LSBProject\RequestBundle\Configuration\Storage;
use LSBProject\RequestBundle\Contract\RequestInterface;
use Symfony\Component\HttpFoundation\Request;

interface MapperInterface
{
    /**
     * @param class-string<RequestInterface> $class
     */
    public function map(string $class, Request $request, Storage $requestStorage = null): object;
}
