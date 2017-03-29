<?php

namespace TyHand\JsonApiToolsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * Build the cofig tree
     *
     * @return TreeBuilder The config tree builder object
     */
    public function getConfigTreeBuilder()
    {
        //Make a new builder
        $builder = new TreeBuilder();

        //Setup the config options
        $builder->root('jsonapi_tools')
        //     ->addDefaultsIfNotSet()
        //     ->children()
        //         ->scalarNode('file_list')->defaultValue('config/file_list.yml')
        //     ->end()
        ;


        return $builder;
    }
}
