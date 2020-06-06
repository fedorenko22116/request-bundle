<?php

namespace LSBProject\RequestBundle\Request\Factory\Param;

use LSBProject\RequestBundle\Configuration\PropConfigurationInterface;
use LSBProject\RequestBundle\Request\Factory\Param\Collection\CollectionParamFactory;
use LSBProject\RequestBundle\Request\Factory\RequestFactoryInterface;
use LSBProject\RequestBundle\Request\Manager\RequestManagerInterface;
use LSBProject\RequestBundle\Util\ReflectionExtractor\DTO\ExtractDTO;
use Symfony\Component\HttpFoundation\Request;

class CompositeFactory implements ParamAwareFactoryInterface
{
    /**
     * @var ParamAwareFactoryInterface[]
     */
    private $composites;

    /**
     * CompositeFactory constructor.
     *
     * @param RequestManagerInterface $requestManager
     * @param RequestFactoryInterface $requestFactory
     */
    public function __construct(RequestManagerInterface $requestManager, RequestFactoryInterface $requestFactory)
    {
        $converterFactory = new ConverterParamFactory($requestManager);

        $this->composites = [
            new CollectionParamFactory($requestManager, $requestFactory, $converterFactory),
            new DtoParamFactory($requestManager, $requestFactory),
            $converterFactory,
            new ScalarParamFactory($requestManager),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function supports(PropConfigurationInterface $configuration)
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function create(ExtractDTO $data, Request $request, PropConfigurationInterface $configuration)
    {
        foreach ($this->composites as $composite) {
            if ($composite->supports($configuration)) {
                return $composite->create($data, $request, $configuration);
            }
        }

        return null;
    }
}
