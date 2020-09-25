<?php

namespace LSBProject\RequestBundle\Util\ReflectionExtractor\Strategy;

use LSBProject\RequestBundle\Configuration\RequestStorage;
use LSBProject\RequestBundle\Util\ReflectionExtractor\DTO\Extraction;
use Reflector;

interface ReflectorExtractorInterface
{
    /**
     * @param Reflector           $reflector
     * @param RequestStorage|null $storage
     *
     * @return Extraction
     */
    public function extract(Reflector $reflector, RequestStorage $storage = null);
}
