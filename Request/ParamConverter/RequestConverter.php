<?php

namespace LSBProject\RequestBundle\Request\ParamConverter;

use LSBProject\RequestBundle\Request\AbstractRequest;
use LSBProject\RequestBundle\Request\Factory\RequestFactoryInterface;
use ReflectionClass;
use ReflectionException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestConverter implements ParamConverterInterface
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var RequestFactoryInterface
     */
    private $requestFactory;

    /**
     * @param ValidatorInterface      $validator
     * @param RequestFactoryInterface $requestFactory
     */
    public function __construct(
        ValidatorInterface $validator,
        RequestFactoryInterface $requestFactory
    ) {
        $this->validator = $validator;
        $this->requestFactory = $requestFactory;
    }

    /**
     * {@inheritDoc}
     * @throws UnprocessableEntityHttpException
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        /** @var class-string<AbstractRequest> $class */
        $class = $configuration->getClass();
        $object = $this->requestFactory->create($class);

        if (($result = $this->validator->validate($object))->count()) {
            throw new UnprocessableEntityHttpException((string) $result->get(0)->getMessage());
        }

        if (!$object->validate()) {
            throw new UnprocessableEntityHttpException($object->getErrorMessage());
        }

        $request->attributes->add([$configuration->getName() => $object]);

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ParamConverter $configuration)
    {
        try {
            /**
             * @template T of object
             *
             * @var class-string<T> $class
             */
            $class = $configuration->getClass();

            return (new ReflectionClass($class))->isSubclassOf(AbstractRequest::class);
        } catch (ReflectionException $exception) {
            return false;
        }
    }
}
