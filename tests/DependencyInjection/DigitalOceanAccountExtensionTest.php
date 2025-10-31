<?php

namespace DigitalOceanAccountBundle\Tests\DependencyInjection;

use DigitalOceanAccountBundle\DependencyInjection\DigitalOceanAccountExtension;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\PHPUnitSymfonyUnitTest\AbstractDependencyInjectionExtensionTestCase;

/**
 * @internal
 */
#[CoversClass(DigitalOceanAccountExtension::class)]
final class DigitalOceanAccountExtensionTest extends AbstractDependencyInjectionExtensionTestCase
{
    private DigitalOceanAccountExtension $extension;

    private ContainerBuilder $container;

    protected function setUp(): void
    {
        parent::setUp();

        $this->extension = new DigitalOceanAccountExtension();
        $this->container = new ContainerBuilder();
        $this->container->setParameter('kernel.environment', 'test');
    }

    public function testExtensionCanBeLoaded(): void
    {
        $this->extension->load([], $this->container);

        self::expectNotToPerformAssertions();
    }
}
