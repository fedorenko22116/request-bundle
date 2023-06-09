<?php

declare(strict_types=1);

namespace LSBProject\RequestBundle\Mapping;

use LSBProject\RequestBundle\Configuration\Storage;
use Symfony\Component\HttpFoundation\Request;

interface DataExtractorInterface
{
    /**
     * @param class-string $schema
     * @return array<string, mixed>
     * @throws \ReflectionException
     */
    public function extract(Request $request, string $schema, ?Storage $baseStorage = null): array;
}
