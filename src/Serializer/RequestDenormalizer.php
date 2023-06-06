<?php

declare(strict_types=1);

namespace LSBProject\RequestBundle\Serializer;

use JetBrains\PhpStorm\ArrayShape;
use LSBProject\RequestBundle\Configuration\Entity;
use LSBProject\RequestBundle\Configuration\RequestStorage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Serializer;

class RequestDenormalizer implements DenormalizerInterface
{
    public const CONTEXT_REQUEST = 'lsb_request';
    public const CONTEXT_REQUEST_STORAGE = 'lsb_request_storage';

    public function __construct(private DenormalizerInterface $decorated)
    {
    }

    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): mixed
    {
        $request = $context[self::CONTEXT_REQUEST] ?? null;
        $requestStorage = $context[self::CONTEXT_REQUEST_STORAGE] ?? null;

        if (!$request) {
            return $this->decorated->denormalize($data, $type, $format, $context);
        }

        $reflectionClass = new \ReflectionClass($type);

        foreach ($reflectionClass->getProperties() as $property) {
            $attribute = $property->getAttributes(Entity::class)[0] ?? null;

            if ($attribute) {
                /** @var Entity $entity */
                $entity = $attribute->newInstance();
            }
        }

        return $this->decorated->denormalize($data, $type, $format, $context);
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null): bool
    {
        return true;
    }
}
