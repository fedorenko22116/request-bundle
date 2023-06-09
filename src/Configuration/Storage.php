<?php

declare(strict_types=1);

namespace LSBProject\RequestBundle\Configuration;

use LSBProject\RequestBundle\Exception\ConfigurationException;

/**
 * @Annotation
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_PROPERTY)]
final class Storage
{
    const TYPES = [
        Source::Header,
        Source::Body,
        Source::Query,
        Source::Path,
        Source::Cookie,
    ];

    /**
     * @param array<Source> $sources
     * @throws ConfigurationException
     */
    public function __construct(public readonly array $sources = self::TYPES)
    {
        foreach ($sources as $source) {
            if (!in_array($source, self::TYPES)) {
                throw new ConfigurationException(
                    sprintf('Unknown storage type. Available types: %s', implode(',', self::TYPES))
                );
            }
        }
    }
}
