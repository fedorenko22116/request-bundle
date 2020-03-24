<?php

namespace LSBProject\RequestBundle\Request;

use Symfony\Component\HttpFoundation\Request;

abstract class AbstractRequest extends Request
{
    /**
     * Method used to add custom validations of input parameters
     *
     * @return bool
     */
    public function validate()
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
