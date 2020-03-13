<?php

namespace LSBProject\RequestBundle\Util\ReflectionExtractor\Strategy;

use Reflector;

class EntityExtractor extends PropertyExtractor
{
    /**
     * {@inheritDoc}
     */
    public function extract(Reflector $reflector)
    {
        $reflector = parent::extract($reflector);
        $reflector->setOptions(array_merge($reflector->getOptions(), [
            'expr' => isset($reflector->getOptions()['expr']) ? $reflector->getOptions()['expr'] : '',
            'meta' => isset($reflector->getOptions()['mapping']) ? $reflector->getOptions()['mapping'] : [],
        ]));

        return $reflector;
    }
}
