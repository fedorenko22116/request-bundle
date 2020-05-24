<?php

namespace LSBProject\RequestBundle\Request\Factory\Param;

use LSBProject\RequestBundle\Configuration\PropConfigurationInterface;
use LSBProject\RequestBundle\Util\ReflectionExtractor\DTO\ExtractDTO;
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
     * @param ExtractDTO                 $data
     * @param Request                    $request
     * @param PropConfigurationInterface $configuration
     *
     * @return mixed
     */
    public function create(ExtractDTO $data, Request $request, PropConfigurationInterface $configuration);
}
