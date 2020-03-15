<?php

namespace LSBProject\RequestBundle\Util\ReflectionExtractor\Strategy;

use LSBProject\RequestBundle\Configuration\RequestStorage;
use Reflector;

class EntityExtractor extends PropertyExtractor
{
    /**
     * {@inheritDoc}
     */
    public function extract(Reflector $reflector, RequestStorage $storage = null)
    {
        $reflector = parent::extract($reflector);
        $configuration = $reflector->getConfiguration();
        $configuration->setOptions(array_merge($configuration->getOptions(), [
            'expr' => isset($configuration->getOptions()['expr']) ? $configuration->getOptions()['expr'] : '',
            'meta' => isset($configuration->getOptions()['mapping']) ? $configuration->getOptions()['mapping'] : [],
        ]));

        return $reflector;
    }
}
