<?php

declare(strict_types=1);

use LSBProject\RequestBundle\ValueResolver;
use LSBProject\RequestBundle\Mapping;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function(ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services
        ->set('lsbproject.request.validator', Mapping\Validator::class)
        ->arg('$container', service('service_container'));

    $services
        ->set('lsbproject.request.data_extractor', Mapping\DataExtractor::class)
        ->arg('$denormalizer', service('serializer'));

    $services
        ->set('lsbproject.request.denormalizer', Mapping\Denormalizer::class)
        ->arg('$decorated', service('serializer'))
        ->arg('$entityValueResolver', service('argument_resolver.query_parameter_value_resolver'));

    $services
        ->set('lsbproject.request.mapper', Mapping\Mapper::class)
        ->arg('$denormalizer', service('lsbproject.request.denormalizer'))
        ->arg('$validator', service('lsbproject.request.validator'))
        ->arg('$dataExtractor', service('lsbproject.request.data_extractor'))
        ->private();

    $services
        ->set('lsbproject.request.value_resolver.attribute', ValueResolver\RequestResolver::class)
        ->arg('$requestFactory', service('lsbproject.request.mapper'))
        ->tag('controller.argument_value_resolver', ['priority' => 100]);

    $services
        ->set('lsbproject.request.value_resolver.object', ValueResolver\RequestAttributeResolver::class)
        ->arg('$requestFactory', service('lsbproject.request.mapper'))
        ->tag('controller.argument_value_resolver', ['priority' => 100]);
};
