<?php

declare(strict_types=1);

namespace LSBProject\RequestBundle\Configuration;

/**
 * @Annotation
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
final class DiscriminatorMap
{
    /**
     * @param array<string, PropConverter|class-string> $mapping
     */
    public function __construct(public readonly string $field, public readonly array $mapping)
    {
    }
}
