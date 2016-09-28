<?php

namespace Toothless\LazyImageBundle\Tests\DependencyInjection\CompilerPass;

use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Toothless\LazyImageBundle\DependencyInjection\CompilerPass\RegisterConverterCompilerPass;

/**
 * Class RegisterConverterCompilerPassTest
 */
class RegisterConverterCompilerPassTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RegisterConverterCompilerPass
     */
    private $pass;

    /**
     * @var ContainerBuilder|PHPUnit_Framework_MockObject_MockObject
     */
    private $container;

    /**
     * @var Definition|PHPUnit_Framework_MockObject_MockObject
     */
    private $definition;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->definition = $this->createMock(Definition::class);

        $this->container = $this->createMock(ContainerBuilder::class);
        $this->container
            ->method('getDefinition')
            ->with('toothless.lazy_image.converter_manager')
            ->willReturn($this->definition);

        $this->pass = new RegisterConverterCompilerPass();
    }


    public function testInstance()
    {
        $pass = new RegisterConverterCompilerPass();
        $this->assertInstanceOf(CompilerPassInterface::class, $pass);
    }

    public function testProcessShouldDoNothingWhenConverterManagerServiceIsNotAvailable()
    {
        $this->definition->expects($this->never())->method('addMethodCall')->withAnyParameters();
        $this->pass->process($this->container);
    }

    /**
     * @param string $id
     * @param array  $attributes
     * @param string $expectedName
     *
     * @dataProvider provideProcessWillAddMethodCallWithExpectedName
     */
    public function testProcessWillAddMethodCallWithExpectedName($id, array $attributes, $expectedName)
    {
        $this->container->method('hasDefinition')->with('toothless.lazy_image.converter_manager')->willReturn(true);
        $this->container->method('findTaggedServiceIds')
            ->with('toothless.lazy_image.converter')
            ->willReturn(
                [
                    $id => [$attributes],
                ]
            );

        $this->definition->expects($this->once())
            ->method('addMethodCall')
            ->with(
                'addConverter',
                $this->callback(
                    function ($subject) use ($expectedName) {
                        return is_array($subject) && $subject[0] === $expectedName && $subject[1] instanceof Reference;
                    }
                )
            );

        $this->pass->process($this->container);
    }

    /**
     * @return array
     */
    public function provideProcessWillAddMethodCallWithExpectedName()
    {
        $id = 'service_foo';
        $alias = 'service_bar';

        return [
            [$id, [], $id],
            [$id, ['alias' => $alias], $alias],
        ];
    }

    /**
     * @param array $services
     * @param int   $expectedAddMethodCall
     *
     * @dataProvider provideProcessShouldAddMethodCallForEachTaggedServicesFound
     */
    public function testProcessShouldAddMethodCallForEachTaggedServicesFound(array $services, $expectedAddMethodCall)
    {
        $this->container->method('hasDefinition')->with('toothless.lazy_image.converter_manager')->willReturn(true);
        $this->container->method('findTaggedServiceIds')->with('toothless.lazy_image.converter')->willReturn($services);

        $this->definition->expects($this->exactly($expectedAddMethodCall))
            ->method('addMethodCall')
            ->with('addConverter', $this->isType('array'));

        $this->pass->process($this->container);
    }

    /**
     * @return array
     */
    public function provideProcessShouldAddMethodCallForEachTaggedServicesFound()
    {
        return [
            [
                [],
                0,
            ],
            [
                [
                    'service_foo' => [[]],
                ],
                1,
            ],
            [
                [
                    'service_foo' => [[]],
                    'service_bar' => [[]],
                ],
                2,
            ],
        ];
    }
}
