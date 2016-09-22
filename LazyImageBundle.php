<?php

namespace Toothless\LazyImageBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Toothless\LazyImageBundle\DependencyInjection\CompilerPass\RegisterConverterCompilerPass;

/**
 * Class LazyImageBundle
 */
class LazyImageBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterConverterCompilerPass());
    }
}
