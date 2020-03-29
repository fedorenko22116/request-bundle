<?php

namespace LSBProject\RequestBundle\Request;

use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractRequest extends Request
{
    /**
     * Method used to add custom validations of input parameters
     *
     * @param ContainerInterface $container
     *
     * @return bool
     */
    public function validate(ContainerInterface $container)
    {
        return true;
    }

    /**
     * Custom error message for invalid data
     *
     * @return string
     */
    public function getErrorMessage()
    {
        return 'Invalid params';
    }
}
