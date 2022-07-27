<?php

namespace LSBProject\RequestBundle\Request\Factory\Param;

use LSBProject\RequestBundle\Util\ReflectionExtractor\Extraction;
use Symfony\Component\HttpFoundation\Request;

interface ParamAwareFactoryInterface
{
    /**
     * @param Extraction $data
     *
     * @return bool
     */
    public function supports(Extraction $data);

    /**
     * @param Extraction $data
     * @param Request    $request
     *
     * @return mixed
     */
    public function create(Extraction $data, Request $request);
}
