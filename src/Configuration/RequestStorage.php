<?php

namespace LSBProject\RequestBundle\Configuration;

use Exception;
use LSBProject\RequestBundle\Exception\ConfigurationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationAnnotation;

/**
 * @Annotation
 */
final class RequestStorage extends ConfigurationAnnotation
{
    const BODY  = 'body';
    const QUERY = 'query';
    const ATTR  = 'attributes';
    const HEAD  = 'head';

    const TYPES = [
        self::BODY,
        self::QUERY,
        self::ATTR,
        self::HEAD,
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
