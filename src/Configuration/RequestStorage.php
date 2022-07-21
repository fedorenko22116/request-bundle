<?php

namespace LSBProject\RequestBundle\Configuration;

use Exception;
use LSBProject\RequestBundle\Exception\ConfigurationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationAnnotation;

/**
 * @Annotation
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_PROPERTY)]
final class RequestStorage extends ConfigurationAnnotation
{
    const BODY  = 'body';
    const QUERY = 'query';
    const PATH  = 'path';
    const HEAD  = 'header';
    const COOKIE  = 'cookie';

    const TYPES = [
        self::BODY,
        self::QUERY,
        self::PATH,
        self::HEAD,
        self::COOKIE,
    ];

    /**
     * @var string[]
     */
    private $sources = self::TYPES;

    /**
     * @var string|null
     */
    private $converter = null;

    /**
     * @param array<string, mixed> $data
     * @param string|null          $converter
     */
    public function __construct(array $data = [], $converter = null)
    {
        $values = [];

        if (is_numeric(array_key_first($data))) {
            $values['value'] = $data;
        } else {
            $values = $data;
        }

        $values['converter'] = isset($values['converter']) ? $values['converter'] : $converter;

        parent::__construct($values);
    }

    /**
     * @param string[] $value
     *
     * @return void
     *
     * @throws Exception
     */
    public function setValue($value)
    {
        $this->setSources($value);
    }

    /**
     * @param string[] $sources
     *
     * @return void
     *
     * @throws Exception
     */
    public function setSources($sources)
    {
        foreach ($sources as $source) {
            if (!in_array($source, self::TYPES)) {
                throw new ConfigurationException(
                    sprintf('Unknown storage type. Available types: %s', implode(',', self::TYPES))
                );
            }
        }

        $this->sources = $sources;
    }

    /**
     * @return string[]
     */
    public function getSources()
    {
        return $this->sources;
    }

    /**
     * @return string|null
     */
    public function getConverter()
    {
        return $this->converter;
    }

    /**
     * @param string|null $converter
     *
     * @return void
     */
    public function setConverter($converter)
    {
        $this->converter = $converter;
    }

    /**
     * {@inheritDoc}
     */
    public function getAliasName()
    {
        return 'converter';
    }

    /**
     * {@inheritDoc}
     */
    public function allowArray()
    {
        return true;
    }
}
