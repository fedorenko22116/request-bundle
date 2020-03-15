<?php

namespace LSBProject\RequestBundle\Request\ParamConverter;

use LSBProject\RequestBundle\Exception\ValidationException;
use LSBProject\RequestBundle\Request\AbstractRequest;
use LSBProject\RequestBundle\Request\Factory\RequestFactoryInterface;
use ReflectionClass;
use ReflectionException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
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
     * @param ValidatorInterface $validator
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
     * @throws ValidationException
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $object = $this->requestFactory->create($configuration->getClass());

        if (($result = $this->validator->validate($object))->count()) {
            throw new ValidationException($result->get(0)->getMessage());
        }

        if (!$object->validate()) {
            throw new ValidationException($object->getErrorMessage());
        }

        $request->attributes->add([$configuration->getName() => $object]);
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ParamConverter $configuration)
    {
        try {
            return (new ReflectionClass($configuration->getClass()))->isSubclassOf(AbstractRequest::class);
        } catch (ReflectionException $exception) {
            return false;
        }
    }
}
