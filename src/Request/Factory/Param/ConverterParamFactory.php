<?php

namespace LSBProject\RequestBundle\Request\Factory\Param;

use LSBProject\RequestBundle\Configuration\PropConfigurationInterface;
use LSBProject\RequestBundle\Request\Manager\RequestManagerInterface;
use LSBProject\RequestBundle\Util\ReflectionExtractor\DTO\Extraction;
use Symfony\Component\HttpFoundation\Request;

final class ConverterParamFactory implements ParamAwareFactoryInterface
{
    /**
     * @var RequestManagerInterface
     */
    private $requestManager;

    /**
     * ConverterParamFactory constructor.
     *
     * @param RequestManagerInterface $requestManager
     */
    public function __construct(RequestManagerInterface $requestManager)
    {

        $this->requestManager = $requestManager;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(PropConfigurationInterface $configuration)
    {
        return !$configuration->isBuiltInType();
    }

    /**
     * {@inheritDoc}
     */
    public function create(Extraction $data, Request $request)
    {
        return $this->requestManager->getFromParamConverters($data, $request);
    }
}
