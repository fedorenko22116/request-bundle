<?php

namespace LSBProject\RequestBundle\Request\Factory\Param\Collection;

use LSBProject\RequestBundle\Configuration\PropConfigurationInterface;
use LSBProject\RequestBundle\Request\Factory\Param\ConverterParamFactory;
use LSBProject\RequestBundle\Request\Factory\Param\ParamAwareFactoryInterface;
use LSBProject\RequestBundle\Request\Factory\RequestFactoryInterface;
use LSBProject\RequestBundle\Request\Manager\RequestManagerInterface;
use LSBProject\RequestBundle\Util\ReflectionExtractor\DTO\Extraction;
use Symfony\Component\HttpFoundation\Request;

final class CollectionParamFactory implements ParamAwareFactoryInterface
{
    /**
     * @var ParamAwareFactoryInterface[] array
     */
    private $factories;

    /**
     * ConverterParamFactory constructor.
     *
     * @param RequestManagerInterface $requestManager
     * @param RequestFactoryInterface $requestFactory
     * @param ConverterParamFactory   $converterParamFactory
     */
    public function __construct(
        RequestManagerInterface $requestManager,
        RequestFactoryInterface $requestFactory,
        ConverterParamFactory $converterParamFactory
    ) {
        $this->factories = [
            new CollectionDtoParamFactory($requestManager, $requestFactory),
            new CollectionConverterParamFactory($requestManager, $converterParamFactory),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function supports(PropConfigurationInterface $configuration)
    {
        return $configuration->isCollection() && $configuration->getType() && !$configuration->isBuiltInType();
    }

    /**
     * {@inheritDoc}
     */
    public function create(Extraction $data, Request $request, PropConfigurationInterface $configuration)
    {
        foreach ($this->factories as $factory) {
            if ($factory->supports($configuration)) {
                return $factory->create($data, $request, $configuration);
            }
        }

        return null;
    }
}
