<?php

namespace LSBProject\RequestBundle\Util\ReflectionExtractor;

use LSBProject\RequestBundle\Configuration\RequestStorage;
use LSBProject\RequestBundle\Util\ReflectionExtractor\DTO\ExtractDTO;
use LSBProject\RequestBundle\Util\ReflectionExtractor\Strategy\ReflectorExtractorInterface;
use Reflector;

interface ReflectorContextInterface
{
    /**
     * @param Reflector           $reflector
     * @param RequestStorage|null $requestStorage
     *
     * @return ExtractDTO
     */
    public function extract(Reflector $reflector, RequestStorage $requestStorage = null);

    /**
     * @param ReflectorExtractorInterface $extractor
     *
     * @return self
     */
    public function setExtractor(ReflectorExtractorInterface $extractor);
}
