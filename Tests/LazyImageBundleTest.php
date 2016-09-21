<?php

namespace Toothless\LazyImageBundle\Tests;

use Symfony\Component\HttpKernel\Bundle\Bundle;
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
}
