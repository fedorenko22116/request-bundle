<?php

namespace LSBProject\RequestBundle\Request\Validator;

use LSBProject\RequestBundle\Request\RequestInterface;

interface RequestValidatorInterface
{
    /**
     * @param RequestInterface $object
     *
     * @return bool
     */
    public function validate($object);

    /**
     * @return string
     */
    public function getError();
}
