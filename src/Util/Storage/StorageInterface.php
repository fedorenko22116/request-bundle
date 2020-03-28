<?php

namespace LSBProject\RequestBundle\Util\Storage;

use LSBProject\RequestBundle\Configuration\RequestStorage;
use Symfony\Component\HttpFoundation\Request;

interface StorageInterface
{
    /**
     * @param string              $param
     * @param RequestStorage|null $paramConfiguration
     * @param Request|null        $request
     *
     * @return string|null
     */
    public function get($param, $paramConfiguration = null, Request $request = null);
}
