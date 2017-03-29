<?php

namespace TyHand\JsonApiToolsBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class FormatterPass implements CompilerPassInterface
{
    /**
     * @{inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        // Check that the resource reader is defined
        if (!$container->has('jsonapi_tools.resource_reader')) {
            return;
        }

        // Get the reader definition
        $definition = $container->findDefinition('jsonapi_tools.resource_reader');

        // Get all the services tagged with the formatter tag
        $tagged = $container->findTaggedServiceIds('jsonapi_tools.formatter');
        foreach($tagged as $id => $tag) {
            // Add to the reader
            $definition->addMethodCall('addFormatter', [new Reference($id)]);
        }
    }
}
