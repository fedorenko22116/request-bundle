<?php

namespace LSBProject\RequestBundle\Request\Manager;

use LSBProject\RequestBundle\Util\ReflectionExtractor\DTO\ExtractDTO;
use Symfony\Component\HttpFoundation\Request;

interface RequestManagerInterface
{
    /**
     * @param ExtractDTO $param
     * @param Request    $request
     *
     * @return mixed|null
     */
    public function get(ExtractDTO $param, Request $request);

    /**
     * @param ExtractDTO $configuration
     * @param Request    $request
     *
     * @return object|null
     */
    public function getFromParamConverters(ExtractDTO $configuration, Request $request);
}
