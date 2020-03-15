<?php

namespace LSBProject\RequestBundle\Request\Manager;

use LSBProject\RequestBundle\Util\ReflectionExtractor\DTO\ExtractDTO;

interface RequestManagerInterface
{
    /**
     * @param ExtractDTO $param
     * @return string|null
     */
    public function get(ExtractDTO $param);

    /**
     * @param ExtractDTO $configuration
     * @return object|null
     */
    public function getFromParamConverters(ExtractDTO $configuration);
}
