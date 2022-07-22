<?php

namespace LSBProject\RequestBundle\Util\Factory;

use LSBProject\RequestBundle\Configuration\Entity;
use LSBProject\RequestBundle\Configuration\PropConfigurationInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

final class ParamConverterFactory implements ParamConverterFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function create($name, PropConfigurationInterface $configuration)
    {
        $paramConverter = new ParamConverter([]);
        $options = $configuration->getOptions();

        if ($configuration instanceof Entity) {
            $options['expr'] = $configuration->getExpr();
        }

        $paramConverter->setOptions($options);
        $paramConverter->setName($name);
        $paramConverter->setIsOptional($configuration->isOptional());

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
