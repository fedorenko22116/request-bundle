<?php

namespace LSBProject\RequestBundle\Util\ReflectionExtractor;

use Doctrine\Common\Annotations\Reader;
use LSBProject\RequestBundle\Configuration\RequestStorage;
use LSBProject\RequestBundle\Util\ReflectionExtractor\Strategy\PropertyExtractor;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use Reflector;

class ReflectionExtractor implements ReflectionExtractorInterface
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
     * @param ReflectorContextInterface $context
     * @param Reader                    $reader
     */
    public function __construct(ReflectorContextInterface $context, Reader $reader)
    {
        $this->context = $context;
        $this->reader = $reader;
    }

    /**
     * {@inheritDoc}
     */
    public function extract(ReflectionClass $class, array $props = [])
    {
        $reflectors = [];

        /** @var RequestStorage|null $requestStorage */
        $requestStorage = $this->reader->getClassAnnotation($class, RequestStorage::class);

        /** @var ReflectionProperty|ReflectionMethod $reflector */
        foreach (array_merge($class->getMethods(), $class->getProperties()) as $reflector) {
            if ($props && !in_array($reflector->getName(), $props)) {
                continue;
            }

            if ($reflector instanceof ReflectionProperty) {
                $reflectors[] = $this->context
                    ->setExtractor(new PropertyExtractor($this->reader))
                    ->extract($reflector, $requestStorage);
            }
        }

        return $reflectors;
    }
}
