<?php

namespace LSBProject\RequestBundle\Util\ReflectionExtractor\Strategy;

use Doctrine\Common\Annotations\Reader;
use LSBProject\RequestBundle\Configuration\Entity;
use LSBProject\RequestBundle\Configuration\PropConverter;
use LSBProject\RequestBundle\Configuration\RequestStorage;
use LSBProject\RequestBundle\Exception\ConfigurationException;
use LSBProject\RequestBundle\Util\ReflectionExtractor\DTO\Extraction;
use LSBProject\RequestBundle\Util\ReflectionExtractor\DTO\Type;
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
     * @return Extraction
     *
     * @throws ConfigurationException
     */
    public function extract(Reflector $reflector, RequestStorage $storage = null)
    {
        if (!$reflector instanceof ReflectionProperty) {
            throw new ConfigurationException('Unsupported extractor type');
        }

        $storage = $this->reader->getPropertyAnnotation($reflector, RequestStorage::class) ?: $storage;

        /** @var PropConverter|null $config */
        $config = $this->reader->getPropertyAnnotation($reflector, PropConverter::class);
        $config = $config ?: $this->reader->getPropertyAnnotation($reflector, Entity::class);
        $config = $config ?: new PropConverter([]);

        if (!$config->getType()) {
            $type = $this->extractType($reflector);

            if ($type->getFirst()) {
                $config->setType($type->getFirst());
                $config->setIsOptional($type->isNullable());
            } elseif (method_exists($reflector, 'getType') && $type = $reflector->getType()) {
                $config->setType($type->getName());
                $config->setIsOptional($type->allowsNull());
            }
        }

        return new Extraction($reflector->getName(), $config, $storage);
    }

    /**
     * @param ReflectionProperty $property
     *
     * @return Type
     */
    private function extractType(ReflectionProperty $property)
    {
        $dto = new Type();
        $docblock = $property->getDocComment();

        if ($docblock
            && preg_match('/@var\s+([^\s]+)/', $docblock, $matches)
            && preg_match_all('/([^|\s]+)*/', $matches[1], $matches)
        ) {
            foreach ($matches[1] as $type) {
                $lowerType = strtolower($type);

                if ($type && 'null' !== $lowerType) {
                    $dto->addValue($type);
                } elseif ('null' === $lowerType) {
                    $dto->setNullable(true);
                }
            }
        }

        return $dto;
    }
}
