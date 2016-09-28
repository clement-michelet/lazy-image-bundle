<?php

namespace Toothless\LazyImageBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class RegisterConverterCompilerPass
 *
 * Register services by adding the following tags :
 * ``̀
 * - {name: "toothless.lazy_image.converter" }
 * ```
 */
class RegisterConverterCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('toothless.lazy_image.converter_manager')) {
            return;
        }

        $definition = $container->getDefinition('toothless.lazy_image.converter_manager');
        $services = $container->findTaggedServiceIds('toothless.lazy_image.converter');

        foreach ($services as $id => $tags) {
            foreach ($tags as $attributes) {
                $name = isset($attributes['alias']) ? $attributes['alias'] : $id;
                $definition->addMethodCall('addConverter', [$name, new Reference($id)]);
            }
        }
    }
}
