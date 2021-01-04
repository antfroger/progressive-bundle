<?php

namespace Af\ProgressiveBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('af_progressive');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('config')
                    ->info('You must indicate a filepath where your features are defined. Ex: %kernel.project_dir%/config/features.yaml')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->arrayNode('context')
                    ->prototype('scalar')->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
