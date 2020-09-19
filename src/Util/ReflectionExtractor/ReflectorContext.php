<?php

namespace LSBProject\RequestBundle\Util\ReflectionExtractor;

use Exception;
use LSBProject\RequestBundle\Configuration\RequestStorage;
use LSBProject\RequestBundle\Util\ReflectionExtractor\Strategy\ReflectorExtractorInterface;
use Reflector;

final class ReflectorContext implements ReflectorContextInterface
{
    /**
     * @var ReflectorExtractorInterface|null
     */
    private $extractor;

    /**
     * {@inheritDoc}
     */
    public function setExtractor(ReflectorExtractorInterface $extractor)
    {
        $this->extractor = $extractor;

        return $this;
    }

    /**
     * {@inheritDoc}
     * @throws Exception
     */
    public function extract(Reflector $reflector, RequestStorage $storage = null)
    {
        if (!$this->extractor) {
            throw new Exception("Extractor is not set");
        }

        return $this->extractor->extract($reflector, $storage);
    }
}
