<?php

namespace Toothless\LazyImageBundle\Tests\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Toothless\LazyImageBundle\DependencyInjection\LazyImageExtension;

/**
 * Class LazyImageExtensionTest
 */
class LazyImageExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $extension = new LazyImageExtension();
        $this->assertInstanceOf(ExtensionInterface::class, $extension);
    }

    public function testLoad()
    {
        $configs = [];
        $container = $this->createMock(ContainerBuilder::class);
        $extension = new LazyImageExtension();

        $container->expects($this->once())->method('addResource')->withAnyParameters();

        $extension->load($configs, $container);
    }
}
