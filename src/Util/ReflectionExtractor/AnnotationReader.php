<?php

namespace LSBProject\RequestBundle\Util\ReflectionExtractor;

use Doctrine\Common\Annotations\Reader;

final class AnnotationReader
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
     * @template T of object
     *
     * @param \ReflectionProperty $property
     * @param class-string<T>     $name
     *
     * @return T|null
     */
    public function getPropertyAnnotation($property, $name)
    {
        $annotation = $this->reader->getPropertyAnnotation($property, $name);

        if (!$annotation && 80000 <= \PHP_VERSION_ID) {
            $attribute = current($property->getAttributes($name));

            if ($attribute) {
                $annotation = $attribute->newInstance();
            }
        }

        return $annotation;
    }

    /**
     * @template T of object
     *
     * @param \ReflectionClass<object> $class
     * @param class-string<T>          $name
     *
     * @return T|null
     */
    public function getClassAnnotation($class, $name)
    {
        $annotation = $this->reader->getClassAnnotation($class, $name);

        if (!$annotation && 80000 <= \PHP_VERSION_ID) {
            $attribute = current($class->getAttributes($name));

            if ($attribute) {
                $annotation = $attribute->newInstance();
            }
        }

        return $annotation;
    }
}
