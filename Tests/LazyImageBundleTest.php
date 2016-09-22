<?php

namespace Toothless\LazyImageBundle\Tests;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Toothless\LazyImageBundle\DependencyInjection\CompilerPass\RegisterConverterCompilerPass;
use Toothless\LazyImageBundle\LazyImageBundle;

/**
 * Class LazyImageBundleTest
 */
class LazyImageBundleTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $bundle = new LazyImageBundle();
        $this->assertInstanceOf(Bundle::class, $bundle);
    }

    public function testBuild()
    {
        $container = $this->createMock(ContainerBuilder::class);

        $container->expects($this->once())
            ->method('addCompilerPass')
            ->with(
                $this->isInstanceOf(RegisterConverterCompilerPass::class)
            );

        $bundle = new LazyImageBundle();
        $bundle->build($container);
    }
}
