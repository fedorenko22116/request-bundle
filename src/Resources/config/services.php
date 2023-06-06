<?php

declare(strict_types=1);

use LSBProject\RequestBundle\Serializer;
use LSBProject\RequestBundle\Validator;
use LSBProject\RequestBundle\ValueResolver;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function(ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    service('serializer.denormalizer.array');
    $services
        ->set('lsbproject.request.validator', Validator\RequestValidator::class)
        ->arg('$container', service('service_container'));

    $services
        ->set('lsbproject.request.denormalizer', Serializer\RequestDenormalizer::class)
        ->arg('$decorated', service('serializer'));

    $services
        ->set('lsbproject.request.factory', Serializer\RequestFactory::class)
        ->arg('$denormalizer', service('lsbproject.request.denormalizer'))
        ->arg('$validator', service('lsbproject.request.validator'))
        ->private();

    $services
        ->set('lsbproject.request.value_resolver.attribute', ValueResolver\RequestResolver::class)
        ->arg('$requestFactory', service('lsbproject.request.factory'))
        ->tag('controller.argument_value_resolver', ['priority' => 100]);;

    $services
        ->set('lsbproject.request.value_resolver.object', ValueResolver\RequestAttributeResolver::class)
        ->arg('$requestFactory', service('lsbproject.request.factory'))
        ->tag('controller.argument_value_resolver', ['priority' => 100]);
};
