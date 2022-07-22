<?php

namespace LSBProject\RequestBundle\Request\Validator;

use Psr\Container\ContainerInterface;

final class RequestValidator implements RequestValidatorInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function validate($object)
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
