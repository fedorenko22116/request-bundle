<?php

namespace LSBProject\RequestBundle\Util\ReflectionExtractor\DTO;

use LSBProject\RequestBundle\Configuration\Discriminator;
use LSBProject\RequestBundle\Configuration\PropConfigurationInterface;
use LSBProject\RequestBundle\Configuration\RequestStorage;

final class Extraction
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
     * @var mixed
     */
    private $default;

    /**
     * @var Discriminator|null
     */
    private $discriminator;

    /**
     * @param string                     $name
     * @param PropConfigurationInterface $configuration
     * @param RequestStorage|null        $requestStorage
     * @param mixed                      $default
     * @param Discriminator|null         $discriminator
     */
    public function __construct(
        $name,
        PropConfigurationInterface $configuration,
        $requestStorage,
        $default = null,
        Discriminator $discriminator = null
    ) {
        $this->name = $name;
        $this->configuration = $configuration;
        $this->requestStorage = $requestStorage;
        $this->default = $default;
        $this->discriminator = $discriminator;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param PropConfigurationInterface $configuration
     */
    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;
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
        return null === $this->default;
    }

    /**
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param mixed $default
     *
     * @return self
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }

    /**
     * @return Discriminator|null
     */
    public function getDiscriminator()
    {
        return $this->discriminator;
    }

    /**
     * @param Discriminator|null $discriminator
     */
    public function setDiscriminator($discriminator)
    {
        $this->discriminator = $discriminator;
    }
}
