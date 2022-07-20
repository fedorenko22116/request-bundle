<?php

namespace LSBProject\RequestBundle\Util\ReflectionExtractor\Strategy;

use LSBProject\RequestBundle\Configuration\Entity;
use LSBProject\RequestBundle\Configuration\PropConverter;
use LSBProject\RequestBundle\Configuration\RequestStorage;
use LSBProject\RequestBundle\Exception\ConfigurationException;
use LSBProject\RequestBundle\Util\ReflectionExtractor\AnnotationReader;
use LSBProject\RequestBundle\Util\ReflectionExtractor\DTO\Extraction;
use ReflectionProperty;
use Reflector;
use Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface;
use Symfony\Component\PropertyInfo\Type;

class PropertyExtractor implements ReflectorExtractorInterface
{
    /**
     * @var AnnotationReader
     */
    private $reader;

    /**
     * @var PropertyInfoExtractorInterface
     */
    private $propertyInfoExtractor;

    /**
     * @param AnnotationReader               $reader
     * @param PropertyInfoExtractorInterface $propertyInfoExtractor
     */
    public function __construct(AnnotationReader $reader, PropertyInfoExtractorInterface $propertyInfoExtractor)
    {
        $this->reader = $reader;
        $this->propertyInfoExtractor = $propertyInfoExtractor;
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
            $types = $this->propertyInfoExtractor->getTypes(
                $reflector->getDeclaringClass()->getName(),
                $reflector->getName()
            );

            if ($types) {
                $type = current($types);

                if ($type->getBuiltinType() === Type::BUILTIN_TYPE_ARRAY) {
                    $config->setIsCollection(true);
                }

                if ($collectionType = $type->getCollectionValueType()) {
                    $type = $collectionType;
                }

                $config->setType($type->getClassName() ?: $type->getBuiltinType());
                $config->setIsOptional($type->isNullable());
            }
        }

        return new Extraction($reflector->getName(), $config, $storage);
    }
}
