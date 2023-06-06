<?php

declare(strict_types=1);

namespace LSBProject\RequestBundle\Serializer;

use LSBProject\RequestBundle\Contract\RequestInterface;
use ReflectionClass;
use ReflectionProperty;
use Symfony\Component\HttpFoundation\Request;

trait RequestPropertyHelperTrait
{
    /**
     * @param ReflectionClass<RequestInterface> $meta
     *
     * @return string[]
     * @throws \ReflectionException
     */
    private function filterProps(ReflectionClass $meta): array
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
