<?php

namespace LSBProject\RequestBundle\Util\ReflectionExtractor;

use LSBProject\RequestBundle\Util\ReflectionExtractor\DTO\ExtractDTO;
use ReflectionClass;

interface ReflectionExtractorInterface
{
    /**
     * @param ReflectionClass $reflector
     * @param string[] $props
     * @return ExtractDTO[]
     */
    public function extract(ReflectionClass $reflector, array $props = []);
}
