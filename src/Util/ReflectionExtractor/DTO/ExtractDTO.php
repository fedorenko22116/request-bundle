<?php

namespace LSBProject\RequestBundle\Util\ReflectionExtractor\DTO;

use LSBProject\RequestBundle\Configuration\PropConfigurationInterface;
use LSBProject\RequestBundle\Configuration\RequestStorage;

class ExtractDTO
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var PropConfigurationInterface
     */
    private $configuration;

    /**
     * @var RequestStorage|null
     */
    private $requestStorage;

    /**
     * @var bool
     */
    private $default;

    /**
     * @param string                     $name
     * @param PropConfigurationInterface $configuration
     * @param RequestStorage|null        $requestStorage
     * @param bool                       $default
     */
    public function __construct($name, PropConfigurationInterface $configuration, $requestStorage, $default = false)
    {
        $this->name = $name;
        $this->configuration = $configuration;
        $this->requestStorage = $requestStorage;
        $this->default = $default;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return PropConfigurationInterface
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @return RequestStorage|null
     */
    public function getRequestStorage()
    {
        return $this->requestStorage;
    }

    /**
     * @param RequestStorage|null $requestStorage
     *
     * @return self
     */
    public function setRequestStorage($requestStorage)
    {
        $this->requestStorage = $requestStorage;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDefault()
    {
        return $this->default;
    }

    /**
     * @param bool $default
     *
     * @return self
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }
}
