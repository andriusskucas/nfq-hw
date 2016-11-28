<?php
namespace WeatherBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('weather');

        $rootNode
            ->children()
                ->scalarNode('provider')
                    ->isRequired()
                    ->defaultValue('cached')
                ->end()
                ->arrayNode('providers')
                    ->children()
                        ->arrayNode('yahoo')
                            ->children()
                                ->scalarNode('base_url')
                                    ->isRequired()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('wunderground')
                            ->children()
                                ->scalarNode('base_url')
                                    ->isRequired()
                                ->end()
                                ->scalarNode('api_key')
                                    ->isRequired()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('delegating')
                            ->children()
                                ->arrayNode('providers')
                                    ->prototype('scalar')->end()
                                    ->defaultValue(['wunderground','yahoo'])
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('cached')
                            ->children()
                                ->scalarNode('provider')
                                    ->defaultValue('wunderground')
                                ->end()
                                ->integerNode('ttl')
                                    ->defaultValue(600)
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}