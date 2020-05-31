<?php

namespace LSBProject\RequestBundle\Request\Factory\Param\Collection;

use LSBProject\RequestBundle\Configuration\PropConfigurationInterface;
use LSBProject\RequestBundle\Request\Factory\Param\ParamAwareFactoryInterface;
use LSBProject\RequestBundle\Request\Factory\Param\RequestCopyTrait;
use LSBProject\RequestBundle\Request\Factory\RequestFactoryInterface;
use LSBProject\RequestBundle\Request\Manager\RequestManagerInterface;
use LSBProject\RequestBundle\Util\ReflectionExtractor\DTO\ExtractDTO;
use Symfony\Component\HttpFoundation\Request;
use LSBProject\RequestBundle\Request\AbstractRequest;

class CollectionEntityParamFactory implements ParamAwareFactoryInterface
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
     * ConverterParamFactory constructor.
     *
     * @param RequestManagerInterface $requestManager
     * @param RequestFactoryInterface $requestFactory
     */
    public function __construct(RequestManagerInterface $requestManager, RequestFactoryInterface $requestFactory)
    {
        $this->requestManager = $requestManager;
        $this->requestFactory = $requestFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(PropConfigurationInterface $configuration)
    {
        return !$configuration->isDto() && !$configuration->isBuiltInType();
    }

    /**
     * {@inheritDoc}
     */
    public function create(ExtractDTO $data, Request $request, PropConfigurationInterface $configuration)
    {
        $params = $this->requestManager->get($data, $request);
        $params = is_array($params) ? $params : [];

        /** @var class-string<AbstractRequest> $type */
        $type = $configuration->getType();

        return array_map(function (array $param) use ($request, $data, $type) {
            return $this->requestFactory->create(
                $type,
                $this->cloneRequest($request, $param, $data->getRequestStorage())
            );
        }, $params);
    }
}
