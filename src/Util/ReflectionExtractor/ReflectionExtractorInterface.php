<?php

namespace LSBProject\RequestBundle\Util\ReflectionExtractor;

use LSBProject\RequestBundle\Request\AbstractRequest;
use LSBProject\RequestBundle\Util\ReflectionExtractor\DTO\Extraction;
use ReflectionClass;

interface ReflectionExtractorInterface
{
    /**
     * @param ReflectionClass<AbstractRequest> $reflector
     * @param string[]                         $props
     *
     * @return array<int, Extraction>
     */
    public function extract(ReflectionClass $reflector, array $props = []);
}
