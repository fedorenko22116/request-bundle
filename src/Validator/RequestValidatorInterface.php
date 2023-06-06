<?php

namespace LSBProject\RequestBundle\Validator;

use LSBProject\RequestBundle\Contract\RequestInterface;

interface RequestValidatorInterface
{
    /**
     * Returns array of errors
     *
     * @param RequestInterface $object
     *
     * @return string[]
     */
    public function validate(object $object): array;
}
