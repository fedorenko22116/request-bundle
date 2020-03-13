<?php

namespace LSBProject\RequestBundle\Util\ReflectionExtractor\Strategy;

use Doctrine\Common\Annotations\Reader;
use Exception;
use LSBProject\RequestBundle\Configuration\PropConverter;
use ReflectionProperty;
use Reflector;

class PropertyExtractor implements ReflectorExtractorInterface
{
    /**
     * @var Reader
     */
    private $reader;

    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * {@inheritDoc}
     * @throws Exception
     */
    public function extract(Reflector $reflector)
    {
        if (!$reflector instanceof ReflectionProperty) {
            throw new Exception('Unsupported extractor type');
        }

        $annotations = $this->reader->getPropertyAnnotations($reflector);

        /** @var PropConverter|false $config */
        $config = current(array_filter($annotations, function ($object) {
            return $object instanceof PropConverter;
        }));
        $config = $config ?: new PropConverter([]);
        $config->setName($reflector->getName());

        if (!$config->getType()) {
            if ($type = $this->extractType($reflector)) {
                $config->setType($this->extractType($reflector));
            } else if (method_exists($reflector, 'getType') && $type = $reflector->getType()) {
                $config->setType($type->getName());
            }
        }

        return $config;
    }

    /**
     * @param ReflectionProperty $property
     * @return string|null
     */
    private function extractType(ReflectionProperty $property)
    {
        $docblock = $property->getDocComment();

        if ($docblock && preg_match('/@var\s+([^\s]+)/', $docblock, $matches)) {
            list(, $type) = $matches;

            return $type;
        }

        return null;
    }
}
