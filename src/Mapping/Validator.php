<?php

declare(strict_types=1);

namespace LSBProject\RequestBundle\Mapping;

use Psr\Container\ContainerInterface;

final class Validator implements ValidatorInterface
{
    public function __construct(private readonly ContainerInterface $container)
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
