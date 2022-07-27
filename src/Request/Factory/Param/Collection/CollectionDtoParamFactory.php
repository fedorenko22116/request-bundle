<?php

namespace LSBProject\RequestBundle\Request\Factory\Param\Collection;

use LSBProject\RequestBundle\Request\Factory\Param\ParamAwareFactoryInterface;
use LSBProject\RequestBundle\Request\Factory\Param\RequestCopyTrait;
use LSBProject\RequestBundle\Request\Factory\RequestFactoryInterface;
use LSBProject\RequestBundle\Request\Manager\RequestManagerInterface;
use LSBProject\RequestBundle\Request\RequestInterface;
use LSBProject\RequestBundle\Util\ReflectionExtractor\Extraction;
use Symfony\Component\HttpFoundation\Request;

final class CollectionDtoParamFactory implements ParamAwareFactoryInterface
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
    public function supports(Extraction $data)
    {
        return $data->getConfiguration()->isDto();
    }

    /**
     * {@inheritDoc}
     */
    public function create(Extraction $data, Request $request)
    {
        $params = $this->requestManager->get($data, $request);
        $params = is_array($params) ? $params : [];

        /** @var class-string<RequestInterface> $type */
        $type = $data->getConfiguration()->getType();

        return array_map(function (array $param) use ($request, $data, $type) {
            return $this->requestFactory->create(
                $type,
                $this->cloneRequest($request, $param, $data->getRequestStorage())
            );
        }, $params);
    }
}
