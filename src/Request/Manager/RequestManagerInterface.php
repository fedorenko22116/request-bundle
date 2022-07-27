<?php

namespace LSBProject\RequestBundle\Request\Manager;

use LSBProject\RequestBundle\Util\ReflectionExtractor\Extraction;
use Symfony\Component\HttpFoundation\Request;

interface RequestManagerInterface
{
    /**
     * @param Extraction $param
     * @param Request    $request
     *
     * @return mixed|null
     */
    public function get(Extraction $param, Request $request);

    /**
     * @param Extraction $configuration
     * @param Request    $request
     *
     * @return object|null
     */
    public function getFromParamConverters(Extraction $configuration, Request $request);
}
