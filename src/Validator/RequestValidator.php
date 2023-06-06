<?php

declare(strict_types=1);

namespace LSBProject\RequestBundle\Validator;

use Psr\Container\ContainerInterface;

final class RequestValidator implements RequestValidatorInterface
{
    public function __construct(private ContainerInterface $container)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function validate(object $object): array
    {
        if ($this->container->has('validator')) {
            $result = $this->container->get('validator')->validate($object);

            return array_map(
                /** @var Symfony\Component\Validator\ConstraintViolationList $error */
                static function ($error) {
                    return $error->getMessage();
                },
                iterator_to_array($result)
            );
        }

        return [];
    }
}
