<?php

namespace LSBProject\RequestBundle\Configuration;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationAnnotation;

/**
 * @Annotation
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
final class Request extends ConfigurationAnnotation
{
    const ALIAS = '_lsbproject_request';

    /**
     * @var string
     */
    private $parameter;

    /**
     * @var RequestStorage
     */
    private $storage;

    /**
     * @param array<string, mixed>|string $parameter
     * @param string|null                 $storage
     */
    public function __construct($parameter = [], $storage = null)
    {
        $values = [];

        if (\is_string($parameter)) {
            $values['value'] = $parameter;
        } else {
            $values = $parameter;
        }

        $values['storage'] = isset($values['storage']) ? $values['storage'] : $storage;

        parent::__construct($values);
    }

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
     * @param RequestStorage $storage
     *
     * @return void
     *
     * @throws Exception
     */
    public function setStorage($storage)
    {
        $this->storage = $storage;
    }

    /**
     * @return RequestStorage
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * {@inheritDoc}
     */
    public function getAliasName()
    {
        return ltrim(self::ALIAS, '_');
    }

    /**
     * {@inheritDoc}
     */
    public function allowArray()
    {
        return false;
    }
}
