<?php

declare(strict_types=1);

namespace LSBProject\RequestBundle\Mapping;

use LSBProject\RequestBundle\Configuration\Storage;
use LSBProject\RequestBundle\Contract\ValidatableInterface;
use LSBProject\RequestBundle\Exception\BadRequestException;
use LSBProject\RequestBundle\Exception\ValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class Mapper implements MapperInterface
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly DataExtractorInterface $dataExtractor,
        private readonly DenormalizerInterface $denormalizer
    ) {
    }

    /**
     * {@inheritdoc}
     *
     * @throws BadRequestException
     */
    public function map($class, Request $request, Storage $requestStorage = null): object
    {
        $data = $this->dataExtractor->extract($request, $class, $requestStorage);
        $object = $this->denormalizer->denormalize($data, $class);

        if ($object instanceof ValidatableInterface) {
            $errors = $this->validator->validate($object);

            if ($errors) {
                throw new ValidationException($errors);
            }
        }

        return $object;
    }
}
