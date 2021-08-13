<?php

namespace LSBProject\RequestBundle\Request\Factory;

use LSBProject\RequestBundle\Request\RequestInterface;
use ReflectionClass;
use ReflectionProperty;
use Symfony\Component\HttpFoundation\Request;

trait RequestPropertyHelperTrait
{
    /**
     * @param ReflectionClass<RequestInterface> $meta
     *
     * @return string[]
     */
    private function filterProps(ReflectionClass $meta)
    {
        $props = array_filter(
            $meta->getProperties(),
            function (ReflectionProperty $prop) use ($meta) {
                $method = 'set' . ucfirst($prop->getName());

                return Request::class !== $prop->getDeclaringClass()->getName() &&
                    ($prop->isPublic() || ($meta->hasMethod($method) && $meta->getMethod($method)->isPublic()));
            }
        );

        return array_map(function (ReflectionProperty $property) {
            return $property->getName();
        }, $props);
    }
}
