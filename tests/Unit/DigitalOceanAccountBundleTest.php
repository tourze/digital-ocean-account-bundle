<?php

namespace DigitalOceanAccountBundle\Tests\Unit;

use DigitalOceanAccountBundle\DigitalOceanAccountBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tourze\BundleDependency\BundleDependencyInterface;

class DigitalOceanAccountBundleTest extends TestCase
{
    private DigitalOceanAccountBundle $bundle;

    protected function setUp(): void
    {
        $this->bundle = new DigitalOceanAccountBundle();
    }

    public function test_bundle_extends_symfony_bundle(): void
    {
        $this->assertInstanceOf(Bundle::class, $this->bundle);
    }

    public function test_bundle_implements_bundle_dependency_interface(): void
    {
        $this->assertInstanceOf(BundleDependencyInterface::class, $this->bundle);
    }

    public function test_get_bundle_dependencies_returns_array(): void
    {
        $dependencies = DigitalOceanAccountBundle::getBundleDependencies();
        
        $this->assertNotEmpty($dependencies);
    }

    public function test_get_bundle_dependencies_contains_required_bundles(): void
    {
        $dependencies = DigitalOceanAccountBundle::getBundleDependencies();
        
        $expectedBundles = [
            \HttpClientBundle\HttpClientBundle::class,
        ];
        
        foreach ($expectedBundles as $expectedBundle) {
            $this->assertArrayHasKey($expectedBundle, $dependencies);
            $this->assertEquals(['all' => true], $dependencies[$expectedBundle]);
        }
    }

    public function test_get_bundle_dependencies_structure(): void
    {
        $dependencies = DigitalOceanAccountBundle::getBundleDependencies();
        
        foreach ($dependencies as $bundleClass => $config) {
            // 验证bundle类名是字符串
            $this->assertIsString($bundleClass);
            
            // 验证配置是数组
            $this->assertIsArray($config);
            
            // 验证配置包含'all'键
            $this->assertArrayHasKey('all', $config);
            $this->assertTrue($config['all']);
        }
    }

    public function test_bundle_class_structure(): void
    {
        $reflection = new \ReflectionClass(DigitalOceanAccountBundle::class);
        
        // 验证类继承关系
        $this->assertTrue($reflection->isSubclassOf(Bundle::class));
        
        // 验证实现的接口
        $interfaces = $reflection->getInterfaceNames();
        $this->assertContains(BundleDependencyInterface::class, $interfaces);
        
        // 验证getBundleDependencies方法存在且为静态
        $this->assertTrue($reflection->hasMethod('getBundleDependencies'));
        $method = $reflection->getMethod('getBundleDependencies');
        $this->assertTrue($method->isStatic());
        $this->assertTrue($method->isPublic());
    }

    public function test_bundle_dependencies_count(): void
    {
        $dependencies = DigitalOceanAccountBundle::getBundleDependencies();
        
        // 验证依赖数量符合预期
        $this->assertCount(1, $dependencies);
    }

    public function test_bundle_name_convention(): void
    {
        $bundleName = $this->bundle->getName();
        
        // Bundle名称应该是DigitalOceanAccountBundle
        $this->assertEquals('DigitalOceanAccountBundle', $bundleName);
    }

    public function test_bundle_namespace(): void
    {
        $reflection = new \ReflectionClass($this->bundle);
        
        $this->assertEquals('DigitalOceanAccountBundle', $reflection->getNamespaceName());
        $this->assertEquals('DigitalOceanAccountBundle', $reflection->getShortName());
    }
}