<?php

namespace LSBProject\RequestBundle\Util\Factory;

use LSBProject\RequestBundle\Configuration\ConfigurationInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

interface ParamConverterFactoryInterface
{
    /**
     * @param ConfigurationInterface $configuration
     * @return ParamConverter
     */
    public function create(ConfigurationInterface $configuration);
}
