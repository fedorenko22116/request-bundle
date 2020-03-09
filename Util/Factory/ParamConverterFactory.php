<?php

namespace LSBProject\RequestBundle\Util\Factory;

use LSBProject\RequestBundle\Configuration\ConfigurationInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ParamConverterFactory implements ParamConverterFactoryInterface
{
    /**
     * @param ConfigurationInterface $configuration
     * @return ParamConverter
     */
    public function create(ConfigurationInterface $configuration)
    {
        $paramConverter = new ParamConverter([]);
        $paramConverter->setName($configuration->getName());
        $paramConverter->setOptions($configuration->getOptions());
        $paramConverter->setIsOptional(false);

        if ($configuration->getType()) {
            $paramConverter->setClass($configuration->getType());
        }

        if ($configuration->getConverter()) {
            $paramConverter->setConverter($configuration->getConverter());
        }

        return $paramConverter;
    }
}
