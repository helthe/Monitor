<?php

/*
 * This file is part of the Helthe Monitor package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Monitor;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Configuration defintion for Helthe Monitor.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('helthe_monitor');

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('api_key')
                    ->info('The project API key')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('endpoint')
                    ->info('The API endpoint to communicate with')
                    ->cannotBeEmpty()
                    ->defaultValue('https://api.helthe.co')
                ->end()
                ->scalarNode('error_level')
                    ->info('Overrides the error level that is used by the error handler')
                    ->cannotBeEmpty()
                    ->defaultNull()
                ->end()
                ->arrayNode('application')
                    ->children()
                    ->scalarNode('root_directory')
                        ->info('The application root directory')
                        ->cannotBeEmpty()
                    ->end()
                    ->scalarNode('version')
                        ->info('The application version')
                    ->end()
                    ->arrayNode('context')
                        ->info('The application context variables')
                        ->prototype('scalar')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
