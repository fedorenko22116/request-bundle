<?php

declare(strict_types=1);

namespace LSBProject\RequestBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('lsb_project_request');

        if (method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC layer for symfony/config 4.1 and older
            $rootNode = $treeBuilder->root('lsb_project_request');
        }

        $rootNode
            ->children()
                ->scalarNode('naming_conversion')->defaultNull()->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
