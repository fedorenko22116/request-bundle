<?php

declare(strict_types=1);

namespace LSBProject\RequestBundle\Mapping;

use LSBProject\RequestBundle\Configuration\Storage;
use LSBProject\RequestBundle\Configuration\Source;
use LSBProject\RequestBundle\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use UnexpectedValueException;

/**
 * Extracts data from request using class schema.
 */
final class DataExtractor implements DataExtractorInterface
{
    private const ROUTE_PARAMETERS_KEY = '_route_params';

    public function __construct(private readonly DenormalizerInterface $denormalizer)
    {
    }

    /**
     * {inheritdoc}
     */
    public function extract(Request $request, string $schema, ?Storage $baseStorage = null): array
    {
        $query = $request->query->all();
        $path = $request->attributes->get(self::ROUTE_PARAMETERS_KEY) ?? [];
        $cookies = $request->cookies->all();
        $files = $request->files->all();
        $headers = $request->headers->all();
        $body = $this->getRequestBody($request);

        $result = [];

        $reflector = new \ReflectionClass($schema);

        foreach ($reflector->getProperties() as $property) {
            $propertyNameAttribute = $property->getAttributes(SerializedName::class)[0] ?? null;

            /** @var SerializedName|null $propertyNameCover */
            $propertyNameCover = $propertyNameAttribute?->newInstance();
            $propertyName = $propertyNameCover?->getSerializedName() ?? $property->getName();

            $storageAttributeReflection = $property->getAttributes(Storage::class)[0] ?? null;

            /** @var Storage $storage */
            $storage = $storageAttributeReflection?->newInstance() ?? $baseStorage ?? new Storage();

            if (!$property->getType()->isBuiltin()) {
                $subRequest = new Request(
                    $query[$propertyName] ?? [],
                    $body[$propertyName] ?? [],
                    [self::ROUTE_PARAMETERS_KEY => $path[$propertyName] ?? null],
                    $cookies[$propertyName] ?? []
                );
                $result[$propertyName] = $this->extract($subRequest, $property->getType()->getName(), $storage);

                continue;
            }

            foreach ($storage->sources as $source) {
                $result[$propertyName] = match($source) {
                    Source::Header => $headers[$propertyName] ?? null,
                    Source::Body => $body[$propertyName] ?? null,
                    Source::Query => $query[$propertyName] ?? null,
                    Source::Path => $path[$propertyName] ?? null,
                    Source::Cookie => $cookies[$propertyName] ?? null,
                    Source::File => $files[$propertyName] ?? null,
                };
            }
        }

        return $result;
    }

    private function getRequestBody(Request $request): array
    {
        $data = $request->request->all();
        $content = $request->getContent();
        $contentType = $request->getContentTypeFormat();

        if (\is_string($content) && null !== $contentType && $contentType !== 'form') {
            try {
                $data = \array_merge($data, $this->denormalizer->decode($content, $contentType));
            } catch (UnexpectedValueException $exception) {
                throw new BadRequestException($exception->getMessage());
            }
        }

        return $data;
    }
}
