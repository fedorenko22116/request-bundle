<?php

namespace LSBProject\RequestBundle\Request\ParamConverter;

use LSBProject\RequestBundle\Configuration\RequestStorage;
use LSBProject\RequestBundle\Request\AbstractRequest;
use LSBProject\RequestBundle\Request\Factory\RequestFactoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use LSBProject\RequestBundle\Configuration\Request as LSBRequest;

class RequestAttributeConverter implements ParamConverterInterface
{
    /**
     * @var RequestFactoryInterface
     */
    private $requestFactory;

    /**
     * @param RequestFactoryInterface $requestFactory
     */
    public function __construct(RequestFactoryInterface $requestFactory)
    {
        $this->requestFactory = $requestFactory;
    }

    /**
     * {@inheritDoc}
     * @throws \Exception
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        if (false === $request->attributes->has(LSBRequest::ALIAS)) {
            return false;
        }

        /** @var LSBRequest $attribute */
        $attribute = $request->attributes->get(LSBRequest::ALIAS);

        if ($configuration->getName() !== $attribute->getParameter()) {
            return false;
        }

        /** @var class-string<AbstractRequest> $class */
        $class = $configuration->getClass();

        $requestStorage = new RequestStorage([]);
        $requestStorage->setSources($attribute->getSources());

        $request->attributes->set(
            $configuration->getName(),
            $this->requestFactory->create($class, $request, null, $requestStorage)
        );

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ParamConverter $configuration)
    {
        return !!$configuration->getClass();
    }
}
