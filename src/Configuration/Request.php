<?php

namespace LSBProject\RequestBundle\Configuration;

use Exception;
use LSBProject\RequestBundle\Exception\ConfigurationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationAnnotation;

/**
 * @Annotation
 */
final class Request extends ConfigurationAnnotation
{
    const ALIAS = 'lsbproject_request';

    /**
     * @var string
     */
    private $parameter;

    /**
     * @var string[]
     */
    private $sources;

    /**
     * @param string $parameter
     *
     * @return self
     */
    public function setValue($parameter)
    {
        $this->parameter = $parameter;

        return $this;
    }

    /**
     * @return string
     */
    public function getParameter()
    {
        return $this->parameter;
    }

    /**
     * @param string $parameter
     *
     * @return self
     */
    public function setParameter($parameter)
    {
        $this->parameter = $parameter;

        return $this;
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
            if (!in_array($source, RequestStorage::TYPES)) {
                throw new ConfigurationException(
                    sprintf('Unknown storage type. Available types: %s', implode(',', RequestStorage::TYPES))
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
     * {@inheritDoc}
     */
    public function getAliasName()
    {
        return self::ALIAS;
    }

    /**
     * {@inheritDoc}
     */
    public function allowArray()
    {
        return false;
    }
}
