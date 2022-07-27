<?php

namespace LSBProject\RequestBundle\Request\Factory\Param;

use LSBProject\RequestBundle\Request\Manager\RequestManagerInterface;
use LSBProject\RequestBundle\Util\ReflectionExtractor\Extraction;
use Symfony\Component\HttpFoundation\Request;

final class ScalarParamFactory implements ParamAwareFactoryInterface
{
    /**
     * @var RequestManagerInterface
     */
    private $requestManager;

    /**
     * ConverterParamFactory constructor.
     *
     * @param RequestManagerInterface $requestManager
     */
    public function __construct(RequestManagerInterface $requestManager)
    {
        $this->requestManager = $requestManager;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(Extraction $data)
    {
        return $data->getConfiguration()->isBuiltInType();
    }

    /**
     * {@inheritDoc}
     */
    public function create(Extraction $data, Request $request)
    {
        return $this->requestManager->get($data, $request);
    }
}
