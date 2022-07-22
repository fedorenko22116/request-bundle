<?php

namespace LSBProject\RequestBundle\Request\Validator;

use LSBProject\RequestBundle\Request\RequestInterface;

interface RequestValidatorInterface
{
    /**
     * Returns array of errors
     *
     * @param RequestInterface $object
     *
     * @return string[]
     */
    public function validate($object);
}
