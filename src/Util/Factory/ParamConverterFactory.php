<?php

namespace LSBProject\RequestBundle\Util\Factory;

use LSBProject\RequestBundle\Configuration\PropConfigurationInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ParamConverterFactory implements ParamConverterFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function create($name, PropConfigurationInterface $configuration)
    {
        $paramConverter = new ParamConverter([]);
        $paramConverter->setName($name);
        $paramConverter->setOptions($configuration->getOptions());
        $paramConverter->setIsOptional(false);

        $type = $configuration->getType();

        if ($type) {
            $paramConverter->setClass($type);
        }

        $converter = $configuration->getConverter();

        if ($converter) {
            $paramConverter->setConverter($converter);
        }

        return $paramConverter;
    }
}
