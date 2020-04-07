<?php

namespace LSBProject\RequestBundle\Util\ReflectionExtractor;

use LSBProject\RequestBundle\Request\AbstractRequest;
use LSBProject\RequestBundle\Util\ReflectionExtractor\DTO\ExtractDTO;
use ReflectionClass;

interface ReflectionExtractorInterface
{
    /**
     * @param ReflectionClass<object> $reflector
     * @param string[]                $props
     *
     * @return array<int, ExtractDTO>
     */
    public function extract(ReflectionClass $reflector, array $props = []);
}
