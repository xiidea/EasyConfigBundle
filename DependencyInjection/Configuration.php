<?php

namespace Xiidea\EasyConfigBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    const ROOT_NODE_NAME = 'xiidea_easy_config';
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder(self::ROOT_NODE_NAME);
        $rootNode = $treeBuilder->getRootNode();

        $this->addRequiredConfigs($rootNode);
        $this->addOptionalConfigs($rootNode);

        return $treeBuilder;
    }

    private function addRequiredConfigs(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->scalarNode('config_class')
                    ->cannotBeOverwritten()
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
            ->end();
    }

    private function addOptionalConfigs(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->variableNode('base_template')
                    ->defaultValue('')
                ->end()
            ->end();
    }
}
