<?php

namespace LSBProject\RequestBundle\Util\Storage;

use LSBProject\RequestBundle\Configuration\RequestStorage;

interface StorageInterface
{
    /**
     * @param string              $param
     * @param RequestStorage|null $paramConfiguration
     *
     * @return string|null
     */
    public function get($param, $paramConfiguration = null);
}
