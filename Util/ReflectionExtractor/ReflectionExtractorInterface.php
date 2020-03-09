<?php

namespace LSBProject\RequestBundle\Util\ReflectionExtractor;

use LSBProject\RequestBundle\Configuration\ConfigurationInterface;
use ReflectionClass;

interface ReflectionExtractorInterface
{
    /**
     * @param ReflectionClass $reflector
     * @param string[] $props
     * @return ConfigurationInterface[]
     */
    public function extract(ReflectionClass $reflector, array $props = []);
}
