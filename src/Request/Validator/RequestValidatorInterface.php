<?php

namespace LSBProject\RequestBundle\Request\Validator;

use LSBProject\RequestBundle\Request\AbstractRequest;

interface RequestValidatorInterface
{
    /**
     * @param AbstractRequest $object
     *
     * @return bool
     */
    public function validate($object);

    /**
     * @return string
     */
    public function getError();
}
