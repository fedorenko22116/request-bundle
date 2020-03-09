<?php

namespace LSBProject\RequestBundle\Util\ReflectionExtractor;

use Exception;
use LSBProject\RequestBundle\Util\ReflectionExtractor\Strategy\ReflectorExtractorInterface;
use Reflector;

class ReflectorContext implements ReflectorContextInterface
{
    /**
     * {@inheritDoc}
     * @var ReflectorExtractorInterface
     */
    private $extractor;

    public function setExtractor(ReflectorExtractorInterface $extractor)
    {
        $this->extractor = $extractor;

        return $this;
    }

    /**
     * {@inheritDoc}
     * @throws Exception
     */
    public function extract(Reflector $reflector)
    {
        if (!$this->extractor) {
            throw new Exception("Extractor is not set");
        }

        return $this->extractor->extract($reflector);
    }
}
