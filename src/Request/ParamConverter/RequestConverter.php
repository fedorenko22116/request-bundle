<?php

namespace LSBProject\RequestBundle\Request\ParamConverter;

use LSBProject\RequestBundle\Request\AbstractRequest;
use LSBProject\RequestBundle\Request\Factory\RequestFactoryInterface;
use LSBProject\RequestBundle\Request\Validator\RequestValidatorInterface;
use ReflectionClass;
use ReflectionException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class RequestConverter implements ParamConverterInterface
{
    /**
     * @var RequestFactoryInterface
     */
    private $requestFactory;

    /**
     * @var RequestValidatorInterface
     */
    private $validator;

    /**
     * @param RequestFactoryInterface   $requestFactory
     * @param RequestValidatorInterface $validator
     */
    public function __construct(
        RequestFactoryInterface $requestFactory,
        RequestValidatorInterface $validator
    ) {
        $this->requestFactory = $requestFactory;
        $this->validator = $validator;
    }

    /**
     * {@inheritDoc}
     * @throws UnprocessableEntityHttpException
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $this->convertRequestContextIfEmpty($request);

        /** @var class-string<AbstractRequest> $class */
        $class = $configuration->getClass();
        $object = $this->requestFactory->create($class);

        if (!$this->validator->validate($object)) {
            throw new UnprocessableEntityHttpException($this->validator->getError());
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

    /**
     * @param Request $request
     *
     * @return void
     */
    private function convertRequestContextIfEmpty(Request $request)
    {
        if ($request->getContentType() !== 'json' || !$request->getContent()) {
            return;
        }

        $data = json_decode((string) $request->getContent(false), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new BadRequestHttpException(sprintf('Invalid json body: %s', json_last_error_msg()));
        }

        $request->request->replace(is_array($data) ? $data : []);
    }
}
