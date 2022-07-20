<?php

namespace LSBProject\RequestBundle\Util\ReflectionExtractor;

use LSBProject\RequestBundle\Configuration\RequestStorage;
use LSBProject\RequestBundle\Util\ReflectionExtractor\Strategy\PropertyExtractor;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use Reflector;
use Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface;

final class ReflectionExtractor implements ReflectionExtractorInterface
{
    /**
     * @var ReflectorContextInterface
     */
    private $context;

    /**
     * @var AnnotationReader
     */
    private $reader;

    /**
     * @var PropertyInfoExtractorInterface
     */
    private $propertyInfoExtractor;

    /**
     * @param ReflectorContextInterface      $context
     * @param AnnotationReader               $reader
     * @param PropertyInfoExtractorInterface $propertyInfoExtractor
     */
    public function __construct(
        ReflectorContextInterface $context,
        AnnotationReader $reader,
        PropertyInfoExtractorInterface $propertyInfoExtractor
    ) {
        $this->context = $context;
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

        /** @var ReflectionProperty|ReflectionMethod $reflector */
        foreach (array_merge($class->getMethods(), $class->getProperties()) as $reflector) {
            if ($props && !in_array($reflector->getName(), $props, true)) {
                continue;
            }

            if ($reflector instanceof ReflectionProperty) {
                $reflector = $this->context
                    ->setExtractor(new PropertyExtractor($this->reader, $this->propertyInfoExtractor))
                    ->extract($reflector, $requestStorage);

                if (array_key_exists($reflector->getName(), $defaultProperties)) {
                    $reflector->setDefault($defaultProperties[$reflector->getName()]);
                }

                $reflectors[] = $reflector;
            }
        }

        return $reflectors;
    }
}
