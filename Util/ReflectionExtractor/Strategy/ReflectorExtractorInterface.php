<?php

namespace LSBProject\RequestBundle\Util\ReflectionExtractor\Strategy;

use LSBProject\RequestBundle\Configuration\ConfigurationInterface;
use Reflector;

interface ReflectorExtractorInterface
{
    /**
     * @param Reflector $reflector
     * @return ConfigurationInterface
     */
    public function extract(Reflector $reflector);
}
