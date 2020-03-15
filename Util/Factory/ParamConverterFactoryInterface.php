<?php

namespace LSBProject\RequestBundle\Util\Factory;

use LSBProject\RequestBundle\Configuration\PropConfigurationInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

interface ParamConverterFactoryInterface
{
    /**
     * @param PropConfigurationInterface $configuration
     *
     * @return ParamConverter
     */
    public function create(PropConfigurationInterface $configuration);
}
