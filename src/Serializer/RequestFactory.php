<?php

declare(strict_types=1);

namespace LSBProject\RequestBundle\Serializer;

use LSBProject\RequestBundle\Configuration\RequestStorage;
use LSBProject\RequestBundle\Contract\RequestInterface;
use LSBProject\RequestBundle\Exception\BadRequestException;
use LSBProject\RequestBundle\Validator\RequestValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Serializer;

final class RequestFactory implements RequestFactoryInterface
{
    use RequestPropertyHelperTrait;

    protected const ROUTE_PARAMETERS_KEY = '_route_params';

    public function __construct(
        private RequestValidatorInterface $validator,
        private DenormalizerInterface $denormalizer
    ) {
    }

    /**
     * {@inheritDoc}
     *
     * @throws BadRequestException
     */
    public function create($class, Request $request, RequestStorage $requestStorage = null): object
    {
        $object = $this->denormalizer->denormalize($this->getAllParameters($request), $class);

//        $errors = $this->validator->validate($object);
//
//        if ($errors) {
//            throw new BadRequestException(current($errors));
//        }

        return $object;
    }

    protected function getAllParameters(Request $request): array
    {
        $parameters = $request->attributes->get(self::ROUTE_PARAMETERS_KEY);
        $data = \array_merge($parameters, $request->query->all(), $request->files->all(), $request->request->all());
        $content = $request->getContent();
        $contentType = $request->getContentTypeFormat();

        if (\is_string($content) && null !== $contentType && $contentType !== 'form') {
            try {
                $data = \array_merge($data, $this->denormalizer->decode($content, $contentType));
            } catch (UnexpectedValueException $exception) {
                throw new BadRequestException($exception->getMessage());
            }
        }

        return $data;
    }
}
