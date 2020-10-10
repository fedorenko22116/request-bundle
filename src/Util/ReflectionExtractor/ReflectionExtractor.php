<?php

namespace LSBProject\RequestBundle\Util\ReflectionExtractor;

use Doctrine\Common\Annotations\Reader;
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
     * @var Reader
     */
    private $reader;

    /**
     * @var PropertyInfoExtractorInterface
     */
    private $propertyInfoExtractor;

    /**
     * @param ReflectorContextInterface      $context
     * @param Reader                         $reader
     * @param PropertyInfoExtractorInterface $propertyInfoExtractor
     */
    public function __construct(
        ReflectorContextInterface $context,
        Reader $reader,
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
            if ($props && !in_array($reflector->getName(), $props)) {
                continue;
            }

            if ($reflector instanceof ReflectionProperty) {
                $reflector = $this->context
                    ->setExtractor(new PropertyExtractor($this->reader, $this->propertyInfoExtractor))
                    ->extract($reflector, $requestStorage);

                if (in_array($reflector->getName(), array_keys($defaultProperties))) {
                    $reflector->setDefault($defaultProperties[$reflector->getName()]);
                }

                $reflectors[] = $reflector;
            }
        }

        return $reflectors;
    }
}
