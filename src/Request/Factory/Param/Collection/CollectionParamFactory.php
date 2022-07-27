<?php

namespace LSBProject\RequestBundle\Request\Factory\Param\Collection;

use LSBProject\RequestBundle\Request\Factory\Param\ConverterParamFactory;
use LSBProject\RequestBundle\Request\Factory\Param\DiscriminatedParamFactory;
use LSBProject\RequestBundle\Request\Factory\Param\ParamAwareFactoryInterface;
use LSBProject\RequestBundle\Request\Factory\RequestFactoryInterface;
use LSBProject\RequestBundle\Request\Manager\RequestManagerInterface;
use LSBProject\RequestBundle\Util\ReflectionExtractor\Extraction;
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
     * @param RequestManagerInterface   $requestManager
     * @param RequestFactoryInterface   $requestFactory
     * @param ConverterParamFactory     $converterParamFactory
     * @param DiscriminatedParamFactory $discriminatedParamFactory
     */
    public function __construct(
        RequestManagerInterface $requestManager,
        RequestFactoryInterface $requestFactory,
        ConverterParamFactory $converterParamFactory,
        DiscriminatedParamFactory $discriminatedParamFactory
    ) {
        $this->factories = [
            new CollectionDiscriminatedParamFactory($requestManager, $discriminatedParamFactory),
            new CollectionDtoParamFactory($requestManager, $requestFactory),
            new CollectionConverterParamFactory($requestManager, $converterParamFactory),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function supports(Extraction $data)
    {
        $configuration = $data->getConfiguration();

        return $configuration->isCollection()
            && ($data->getDiscriminator() || ($configuration->getType() && !$configuration->isBuiltInType()));
    }

    /**
     * {@inheritDoc}
     */
    public function create(Extraction $data, Request $request)
    {
        foreach ($this->factories as $factory) {
            if ($factory->supports($data)) {
                return $factory->create($data, $request);
            }
        }

        return null;
    }
}
