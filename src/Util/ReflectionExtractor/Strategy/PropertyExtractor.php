<?php

namespace LSBProject\RequestBundle\Util\ReflectionExtractor\Strategy;

use Doctrine\Common\Annotations\Reader;
use Exception;
use LSBProject\RequestBundle\Configuration\Entity;
use LSBProject\RequestBundle\Configuration\PropConverter;
use LSBProject\RequestBundle\Configuration\RequestStorage;
use LSBProject\RequestBundle\Util\ReflectionExtractor\DTO\ExtractDTO;
use ReflectionProperty;
use Reflector;

class PropertyExtractor implements ReflectorExtractorInterface
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     *
     * @param ReflectionProperty|Reflector $reflector
     * @param RequestStorage|null          $storage
     *
     * @return ExtractDTO
     *
     * @throws Exception
     */
    public function extract(Reflector $reflector, RequestStorage $storage = null)
    {
        if (!$reflector instanceof ReflectionProperty) {
            throw new Exception('Unsupported extractor type');
        }

        $storage = $this->reader->getPropertyAnnotation($reflector, RequestStorage::class) ?: $storage;

        /** @var PropConverter|null $config */
        $config = $this->reader->getPropertyAnnotation($reflector, PropConverter::class);
        $config = $config ?: $this->reader->getPropertyAnnotation($reflector, Entity::class);
        $config = $config ?: new PropConverter([]);

        if (!$config->getType()) {
            if ($type = $this->extractType($reflector)) {
                $config->setType($type);
            } elseif (method_exists($reflector, 'getType') && $type = $reflector->getType()) {
                $config->setType($type->getName());
                $config->setIsOptional($type->allowsNull());
            }
        }

        return new ExtractDTO($reflector->getName(), $config, $storage);
    }

    /**
     * @param ReflectionProperty $property
     *
     * @return string|null
     */
    private function extractType(ReflectionProperty $property)
    {
        $docblock = $property->getDocComment();

        if ($docblock && preg_match('/@var\s+([^\s]+)/', $docblock, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
