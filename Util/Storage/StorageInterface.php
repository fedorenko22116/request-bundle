<?php

namespace LSBProject\RequestBundle\Util\Storage;

use LSBProject\RequestBundle\Configuration\RequestStorage;
use Symfony\Component\HttpFoundation\ParameterBag;

interface StorageInterface
{
    /**
     * @param string $param
     * @param RequestStorage|null $paramConfiguration
     * @return ParameterBag
     */
    public function get($param, $paramConfiguration = null);
}
