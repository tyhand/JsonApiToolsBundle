<?php

namespace TyHand\JsonApiToolsBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TyHandJsonApiToolsExtension extends Extension
{
    /**
     * Load the configurations settings
     *
     * @param  array            $configs   The config options
     * @param  ContainerBuilder $container The container builder
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        //Load the configuration
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration( $configuration, $configs );
        $loader = new YamlFileLoader($container, new FileLocator( __DIR__.'/../Resources/config' ) );
        $loader->load('services.yml');
    }


    /**
     * Get the configuration for the document downloader bundle
     *
     * @param  array            $config    The array of config settings
     * @param  ContainerBuilder $container The container builder
     *
     * @return Configuration               The bundles configuration
     */
    public function getConfiguration(array $config, ContainerBuilder $container)
    {
        return new Configuration();
    }


    /**
     * Get the alias of the configuration
     *
     * @return string The alias
     */
    public function getAlias()
    {
        return 'ty_hand_json_api_tools';
    }
}
