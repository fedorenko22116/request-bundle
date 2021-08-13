<?php

namespace LSBProject\RequestBundle\Request\Validator;

use Psr\Container\ContainerInterface;

final class RequestValidator implements RequestValidatorInterface
{
    /**
     * @var string
     */
    private $message = '';

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
            if (($result = $this->container->get('validator')->validate($object))->count()) {
                $this->message = (string) $result->get(0)->getMessage();

                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function getError()
    {
        return $this->message;
    }
}
