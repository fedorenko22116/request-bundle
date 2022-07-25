<?php

namespace LSBProject\RequestBundle\Request\ParamConverter;

use LSBProject\RequestBundle\Configuration\RequestStorage;
use LSBProject\RequestBundle\Request\RequestInterface;
use LSBProject\RequestBundle\Request\Factory\RequestFactoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use LSBProject\RequestBundle\Configuration\Request as LSBRequest;

final class RequestAttributeConverter implements ParamConverterInterface
{
    use ContentTypeHelperTrait;

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
        if (!$request->attributes->has(LSBRequest::ALIAS)) {
            return false;
        }

        /** @var LSBRequest $attribute */
        $attribute = $request->attributes->get(LSBRequest::ALIAS);

        if ($configuration->getName() !== $attribute->getParameter()) {
            return false;
        }

        $this->convertRequestContextIfEmpty($request);

        /** @var class-string<RequestInterface> $class */
        $class = $configuration->getClass();

        $request->attributes->set(
            $configuration->getName(),
            $this->requestFactory->create($class, $request, $attribute->getStorage())
        );

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ParamConverter $configuration)
    {
        return (bool) $configuration->getClass();
    }
}
