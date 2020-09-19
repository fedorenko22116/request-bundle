<?php

namespace LSBProject\RequestBundle\Request\Factory\Param\Collection;

use LSBProject\RequestBundle\Configuration\PropConfigurationInterface;
use LSBProject\RequestBundle\Request\Factory\Param\ConverterParamFactory;
use LSBProject\RequestBundle\Request\Factory\Param\ParamAwareFactoryInterface;
use LSBProject\RequestBundle\Request\Factory\Param\RequestCopyTrait;
use LSBProject\RequestBundle\Request\Factory\RequestFactoryInterface;
use LSBProject\RequestBundle\Request\Manager\RequestManagerInterface;
use LSBProject\RequestBundle\Util\ReflectionExtractor\DTO\ExtractDTO;
use Symfony\Component\HttpFoundation\Request;
use LSBProject\RequestBundle\Request\AbstractRequest;

final class CollectionConverterParamFactory implements ParamAwareFactoryInterface
{
    use RequestCopyTrait;

    /**
     * @var RequestManagerInterface
     */
    private $requestManager;

    /**
     * @var RequestFactoryInterface
     */
    private $requestFactory;

    /**
     * @var ConverterParamFactory
     */
    private $converterParamFactory;

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
        $this->requestManager = $requestManager;
        $this->requestFactory = $requestFactory;
        $this->converterParamFactory = $converterParamFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(PropConfigurationInterface $configuration)
    {
        return !$configuration->isDto();
    }

    /**
     * {@inheritDoc}
     */
    public function create(ExtractDTO $data, Request $request, PropConfigurationInterface $configuration)
    {
        $params = $this->requestManager->get($data, $request);
        $params = is_array($params) ? $params : [];

        return array_map(function (array $param) use ($request, $data, $configuration) {
            return $this->converterParamFactory->create(
                $data,
                $this->cloneRequest($request, $param, $data->getRequestStorage()),
                $configuration
            );
        }, $params);
    }
}
