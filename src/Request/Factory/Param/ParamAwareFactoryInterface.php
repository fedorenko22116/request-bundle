<?php

namespace LSBProject\RequestBundle\Request\Factory\Param;

use LSBProject\RequestBundle\Configuration\PropConfigurationInterface;
use LSBProject\RequestBundle\Util\ReflectionExtractor\DTO\Extraction;
use Symfony\Component\HttpFoundation\Request;

interface ParamAwareFactoryInterface
{
    /**
     * @param PropConfigurationInterface $configuration
     *
     * @return bool
     */
    public function supports(PropConfigurationInterface $configuration);

    /**
     * @param Extraction $data
     * @param Request    $request
     *
     * @return mixed
     */
    public function create(Extraction $data, Request $request);
}
