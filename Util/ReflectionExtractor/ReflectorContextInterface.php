<?php

namespace LSBProject\RequestBundle\Util\ReflectionExtractor;

use LSBProject\RequestBundle\Configuration\ConfigurationInterface;
use LSBProject\RequestBundle\Util\ReflectionExtractor\Strategy\ReflectorExtractorInterface;
use Reflector;

interface ReflectorContextInterface
{
    /**
     * @param Reflector $reflector
     * @return ConfigurationInterface
     */
    public function extract(Reflector $reflector);

    /**
     * @param ReflectorExtractorInterface $extractor
     * @return $this
     */
    public function setExtractor(ReflectorExtractorInterface $extractor);
}
