<?php

namespace Xiidea\EasyConfigBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Xiidea\EasyConfigBundle\DependencyInjection\Compiler\ConfigGroupCompilerPass;

class XiideaEasyConfigBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new ConfigGroupCompilerPass());
    }
}
