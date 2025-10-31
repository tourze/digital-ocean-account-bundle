<?php

declare(strict_types=1);

namespace DigitalOceanAccountBundle\Tests\Service;

use DigitalOceanAccountBundle\Service\AdminMenu;
use DigitalOceanAccountBundle\Tests\Helper\TestMenuItem;
use Knp\Menu\ItemInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminMenuTestCase;

/**
 * AdminMenu 单元测试
 *
 * @internal
 */
#[CoversClass(AdminMenu::class)]
#[RunTestsInSeparateProcesses]
final class AdminMenuTest extends AbstractEasyAdminMenuTestCase
{
    private ItemInterface $item;

    public function testInvokeMethod(): void
    {
        // 测试 AdminMenu 的 __invoke 方法正常工作
        $this->expectNotToPerformAssertions();

        try {
            $adminMenu = self::getService(AdminMenu::class);
            ($adminMenu)($this->item);
        } catch (\Throwable $e) {
            self::fail('AdminMenu __invoke method should not throw exception: ' . $e->getMessage());
        }
    }

    protected function onSetUp(): void
    {
        // 使用测试辅助类替代复杂的匿名类
        $childItem = new TestMenuItem();
        $childItem->setName('child');

        $this->item = new TestMenuItem();
        $this->item->setName('root');
        $this->item->addChild($childItem);
    }
}
