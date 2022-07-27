<?php

namespace LSBProject\RequestBundle\Util\ReflectionExtractor;

use LSBProject\RequestBundle\Request\RequestInterface;
use ReflectionClass;

interface ReflectionExtractorInterface
{
    /**
     * @param ReflectionClass<RequestInterface> $reflector
     * @param string[]                          $props
     *
     * @return array<int, Extraction>
     */
    public function extract(ReflectionClass $reflector, array $props = []);
}
