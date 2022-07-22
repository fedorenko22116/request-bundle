<?php

namespace LSBProject\RequestBundle\Request\Factory\Param\Collection;

use LSBProject\RequestBundle\Configuration\PropConfigurationInterface;
use LSBProject\RequestBundle\Request\Factory\Param\ConverterParamFactory;
use LSBProject\RequestBundle\Request\Factory\Param\ParamAwareFactoryInterface;
use LSBProject\RequestBundle\Request\Factory\Param\RequestCopyTrait;
use LSBProject\RequestBundle\Request\Manager\RequestManagerInterface;
use LSBProject\RequestBundle\Util\ReflectionExtractor\DTO\Extraction;
use Symfony\Component\HttpFoundation\Request;

final class CollectionConverterParamFactory implements ParamAwareFactoryInterface
{
    use RequestCopyTrait;

    /**
     * @var RequestManagerInterface
     */
    private $requestManager;

    /**
     * @var ConverterParamFactory
     */
    private $converterParamFactory;

    /**
     * ConverterParamFactory constructor.
     *
     * @param RequestManagerInterface $requestManager
     * @param ConverterParamFactory   $converterParamFactory
     */
    public function __construct(
        RequestManagerInterface $requestManager,
        ConverterParamFactory $converterParamFactory
    ) {
        $this->requestManager = $requestManager;
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
    public function create(Extraction $data, Request $request)
    {
        $params = $this->requestManager->get($data, $request);
        $params = is_array($params) ? $params : [];

        return array_map(function (array $param) use ($request, $data) {
            return $this->converterParamFactory->create(
                $data,
                $this->cloneRequest($request, $param, $data->getRequestStorage())
            );
        }, $params);
    }
}
