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
    private $isDto;

    /**
     * @param string                     $name
     * @param bool                       $isDto
     * @param PropConfigurationInterface $configuration
     * @param RequestStorage|null        $requestStorage
     */
    public function __construct($name, $isDto, PropConfigurationInterface $configuration, $requestStorage)
    {
        $this->name = $name;
        $this->isDto = $isDto;
        $this->configuration = $configuration;
        $this->requestStorage = $requestStorage;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isDto()
    {
        return $this->isDto;
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
}
