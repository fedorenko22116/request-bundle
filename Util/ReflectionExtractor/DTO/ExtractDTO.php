<?php

namespace LSBProject\RequestBundle\Util\ReflectionExtractor\DTO;

use LSBProject\RequestBundle\Configuration\PropConfigurationInterface;
use LSBProject\RequestBundle\Configuration\RequestStorage;

class ExtractDTO
{
    /**
     * @var PropConfigurationInterface
     */
    private $configuration;

    /**
     * @var RequestStorage|null
     */
    private $requestStorage;

    /**
     * @param PropConfigurationInterface $configuration
     * @param RequestStorage|null $requestStorage
     */
    public function __construct(PropConfigurationInterface $configuration, $requestStorage)
    {
        $this->configuration = $configuration;
        $this->requestStorage = $requestStorage;
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
