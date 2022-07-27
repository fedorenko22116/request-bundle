<?php

namespace LSBProject\RequestBundle\Util\ReflectionExtractor;

use LSBProject\RequestBundle\Configuration\Discriminator;
use LSBProject\RequestBundle\Configuration\Entity;
use LSBProject\RequestBundle\Configuration\PropConverter;
use LSBProject\RequestBundle\Configuration\RequestStorage;
use LSBProject\RequestBundle\Exception\ConfigurationException;
use ReflectionClass;
use ReflectionProperty;
use Reflector;
use Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface;
use Symfony\Component\PropertyInfo\Type;

final class ReflectionExtractor implements ReflectionExtractorInterface
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
     * {@inheritDoc}
     */
    public function extract(ReflectionClass $class, array $props = [])
    {
        $reflectors = [];

        /** @var RequestStorage|null $requestStorage */
        $requestStorage = $this->reader->getClassAnnotation($class, RequestStorage::class);
        $defaultProperties = $class->getDefaultProperties();

        /** @var ReflectionProperty $reflector */
        foreach ($class->getProperties() as $reflector) {
            if ($props && !in_array($reflector->getName(), $props, true)) {
                continue;
            }

            $reflector = $this->extractProperty($reflector, $requestStorage);

            if (array_key_exists($reflector->getName(), $defaultProperties)) {
                $reflector->setDefault($defaultProperties[$reflector->getName()]);
            }

            $reflectors[] = $reflector;
        }

        return $reflectors;
    }

    /**
     * @param ReflectionProperty|Reflector $reflector
     * @param RequestStorage|null          $storage
     *
     * @return Extraction
     *
     * @throws ConfigurationException
     */
    private function extractProperty(Reflector $reflector, RequestStorage $storage = null)
    {
        if (!$reflector instanceof ReflectionProperty) {
            throw new ConfigurationException('Unsupported extractor type');
        }

        $storage = $this->reader->getPropertyAnnotation($reflector, RequestStorage::class) ?: $storage;
        $discriminator = $this->reader->getPropertyAnnotation($reflector, Discriminator::class);

        $config = $this->reader->getPropertyAnnotation($reflector, PropConverter::class);
        $config = $config ?: $this->reader->getPropertyAnnotation($reflector, Entity::class);
        $config = $config ?: new PropConverter([]);

        $types = $this->propertyInfoExtractor->getTypes(
            $reflector->getDeclaringClass()->getName(),
            $reflector->getName()
        );

        if ($types) {
            $type = current($types);

            if ($type->getBuiltinType() === Type::BUILTIN_TYPE_ARRAY) {
                $config->setIsCollection(true);
            }

            if (method_exists($type, 'getCollectionValueType') && $collectionType = $type->getCollectionValueType()) {
                $type = $collectionType;
            }

            if (!$config->getType()) {
                $config->setType($type->getClassName() ?: $type->getBuiltinType());
            }

            $config->setIsOptional($type->isNullable());
        }

        return new Extraction($reflector->getName(), $config, $storage, null, $discriminator);
    }
}
