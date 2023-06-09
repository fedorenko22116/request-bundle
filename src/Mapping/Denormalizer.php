<?php

declare(strict_types=1);

namespace LSBProject\RequestBundle\Mapping;

use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class Denormalizer implements DenormalizerInterface
{
    public function __construct(
        private readonly DenormalizerInterface $decorated,
        private readonly ValueResolverInterface $entityValueResolver,
    ) {
    }

    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): mixed
    {
        $object = $this->decorated->denormalize($data, $type, $format, $context);
        $isMapEntityExists = class_exists('Symfony\Bridge\Doctrine\Attribute\MapEntity');
        $reflectionClass = new \ReflectionClass($type);

        foreach ($reflectionClass->getProperties() as $property) {
            if ($isMapEntityExists) {
                $attribute = $property->getAttributes(MapEntity::class)[0] ?? null;

                if ($attribute) {
                    /** @var MapEntity $entity */
                    $entityMap = $attribute->newInstance();
                    $request = new Request($data);
                    $argumentMetadata = new ArgumentMetadata(
                        $property->getName(),
                        $property->getType()->getName(),
                        false,
                        $property->hasDefaultValue(),
                        $property->getDefaultValue(),
                        $property->getType()->allowsNull(),
                        [$entityMap]
                    );

                    $this->entityValueResolver->resolve($request, $argumentMetadata);

                    $object->{$property->getName()} = $request->attributes->get($property->getName());
                }
            }
        }

        return $object;
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null): bool
    {
        return true;
    }
}
