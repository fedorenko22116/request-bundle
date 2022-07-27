<?php

namespace LSBProject\RequestBundle\Request\Factory\Param;

use LSBProject\RequestBundle\Request\Factory\Param\Collection\CollectionParamFactory;
use LSBProject\RequestBundle\Request\Factory\RequestFactoryInterface;
use LSBProject\RequestBundle\Request\Manager\RequestManagerInterface;
use LSBProject\RequestBundle\Util\ReflectionExtractor\Extraction;
use Symfony\Component\HttpFoundation\Request;

final class CompositeFactory implements ParamAwareFactoryInterface
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
        $discriminatedParamFactory = new DiscriminatedParamFactory($this);
        $converterFactory = new ConverterParamFactory($requestManager);
        $collectionParamFactory = new CollectionParamFactory(
            $requestManager,
            $requestFactory,
            $converterFactory,
            $discriminatedParamFactory
        );

        $this->composites = [
            $collectionParamFactory,
            new EnumParamFactory($requestManager),
            $discriminatedParamFactory,
            new DtoParamFactory($requestManager, $requestFactory),
            $converterFactory,
            new ScalarParamFactory($requestManager),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function supports(Extraction $data)
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function create(Extraction $data, Request $request)
    {
        foreach ($this->composites as $composite) {
            if ($composite->supports($data)) {
                return $composite->create($data, $request);
            }
        }

        return null;
    }
}
