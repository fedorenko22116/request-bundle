<?php

namespace LSBProject\RequestBundle\Mapping;

use LSBProject\RequestBundle\Contract\RequestInterface;

interface ValidatorInterface
{
    /**
     * Returns array of errors
     *
     * @param RequestInterface $object
     *
     * @return array<string, string>
     */
    public function validate(object $object): array;
}
