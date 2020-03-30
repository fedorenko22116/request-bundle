<?php

namespace LSBProject\RequestBundle\Util\ReflectionExtractor\Strategy;

use Doctrine\Common\Annotations\Reader;
use Exception;
use LSBProject\RequestBundle\Configuration\Entity;
use LSBProject\RequestBundle\Configuration\PropConverter;
use LSBProject\RequestBundle\Configuration\RequestStorage;
use LSBProject\RequestBundle\Util\ReflectionExtractor\DTO\ExtractDTO;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionProperty;
use Reflector;

class PropertyExtractor implements ReflectorExtractorInterface
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param Reader             $reader
     * @param ContainerInterface $container
     */
    public function __construct(Reader $reader, ContainerInterface $container)
    {
        $this->reader = $reader;
        $this->container = $container;
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
                $config->setType($this->extractType($reflector));
            } elseif (method_exists($reflector, 'getType') && $type = $reflector->getType()) {
                $config->setType($type->getName());
                $config->setIsOptional($type->allowsNull());
            }
        }

        $isDto = true;
        $type = $config->getType();

        if ($this->container->has('doctrine') && $type) {
            $class = new ReflectionClass($type);
            $annotation = $this->reader->getClassAnnotation($class, 'Doctrine\ORM\Mapping\Entity');

            if ($annotation) {
                if (is_object($class)) {
                    $class = 'Doctrine\Common\Persistence\Proxy' === get_class($class)
                        ? get_parent_class($class)
                        : get_class($class);
                }

                $isDto = $this->container->get('doctrine')->getManager()->getMetadataFactory()->isTransient($class);
            }
        }

        return new ExtractDTO($reflector->getName(), $isDto, $config, $storage);
    }

    /**
     * @param ReflectionProperty $property
     *
     * @return string|null
     */
    private function extractType(ReflectionProperty $property)
    {
        $docblock = $property->getDocComment();

        if ($docblock && preg_match('/@var\s+([^\s]+)\|?/', $docblock, $matches)) {
            list(, $type) = $matches;

            return $type;
        }

        return null;
    }
}
