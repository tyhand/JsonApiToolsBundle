<?php

namespace TyHand\JsonApiToolsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use TyHand\JsonApiToolsBundle\DependencyInjection\Compiler\FormatterPass;
use TyHand\JsonApiToolsBundle\DependencyInjection\Compiler\ResourcePass;

class TyHand\JsonApiToolsBundle extends Bundle
{
    /**
     * @{inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new FormatterPass());
        $container->addCompilerPass(new ResourcePass());
    }
}
