<?php

namespace LSBProject\RequestBundle\Request\Factory\Param;

use LSBProject\RequestBundle\Request\Factory\RequestFactoryInterface;
use LSBProject\RequestBundle\Request\Manager\RequestManagerInterface;
use LSBProject\RequestBundle\Request\RequestInterface;
use LSBProject\RequestBundle\Util\ReflectionExtractor\Extraction;
use Symfony\Component\HttpFoundation\Request;

final class DtoParamFactory implements ParamAwareFactoryInterface
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
        $configuration = $data->getConfiguration();

        return $configuration->isDto()
            && $configuration->getType()
            && !$configuration->isBuiltInType()
            && !$configuration->isCollection();
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

        return $this->requestFactory->create(
            $type,
            $this->cloneRequest($request, $params, $data->getRequestStorage())
        );
    }
}
