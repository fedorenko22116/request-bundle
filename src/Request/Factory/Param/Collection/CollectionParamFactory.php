<?php

namespace LSBProject\RequestBundle\Request\Factory\Param\Collection;

use LSBProject\RequestBundle\Configuration\PropConfigurationInterface;
use LSBProject\RequestBundle\Request\Factory\Param\ParamAwareFactoryInterface;
use LSBProject\RequestBundle\Request\Factory\RequestFactoryInterface;
use LSBProject\RequestBundle\Request\Manager\RequestManagerInterface;
use LSBProject\RequestBundle\Util\ReflectionExtractor\DTO\ExtractDTO;
use Symfony\Component\HttpFoundation\Request;

class CollectionParamFactory implements ParamAwareFactoryInterface
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
     */
    public function __construct(RequestManagerInterface $requestManager, RequestFactoryInterface $requestFactory)
    {
        $this->factories = [
            new CollectionDtoParamFactory($requestManager, $requestFactory),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function supports(PropConfigurationInterface $configuration)
    {
        return $configuration->isCollection() && $configuration->getType();
    }

    /**
     * {@inheritDoc}
     */
    public function create(ExtractDTO $data, Request $request, PropConfigurationInterface $configuration)
    {
        foreach ($this->factories as $factory) {
            if ($factory->supports($configuration)) {
                return $factory->create($data, $request, $configuration);
            }
        }

        return null;
    }
}
