<?php

namespace Xiidea\EasyConfigBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ConfigGroupCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (false === $container->hasDefinition('xiidea.easy_config.service_manager')) {
            return;
        }

        $definition = $container->getDefinition('xiidea.easy_config.service_manager');

        foreach ($container->findTaggedServiceIds('xiidea.easy_config.group') as $id => $attributes) {
            $definition->addMethodCall('addConfigGroup', [new Reference($id)]);
        }
    }
}
