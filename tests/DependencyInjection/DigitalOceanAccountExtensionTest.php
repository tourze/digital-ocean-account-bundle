<?php

namespace DigitalOceanAccountBundle\Tests\DependencyInjection;

use DigitalOceanAccountBundle\DependencyInjection\DigitalOceanAccountExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DigitalOceanAccountExtensionTest extends TestCase
{
    private DigitalOceanAccountExtension $extension;
    private ContainerBuilder $container;

    protected function setUp(): void
    {
        $this->extension = new DigitalOceanAccountExtension();
        $this->container = new ContainerBuilder();
    }

    public function testLoadConfigures(): void
    {
        $this->extension->load([], $this->container);

        self::assertTrue($this->container->hasParameter('digital_ocean_account.resource_dir'));
        self::assertSame(
            dirname(__DIR__, 2) . '/src/Resources/config',
            $this->container->getParameter('digital_ocean_account.resource_dir')
        );
    }

    public function testLoadServicesFile(): void
    {
        $this->extension->load([], $this->container);

        // 检查是否加载了 services.yaml
        self::assertTrue($this->container->hasDefinition('DigitalOceanAccountBundle\Client\DigitalOceanClient'));
        self::assertTrue($this->container->hasDefinition('DigitalOceanAccountBundle\Service\AccountService'));
        self::assertTrue($this->container->hasDefinition('DigitalOceanAccountBundle\Service\DigitalOceanConfigService'));
    }
}