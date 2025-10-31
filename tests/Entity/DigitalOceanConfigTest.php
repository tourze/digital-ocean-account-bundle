<?php

namespace DigitalOceanAccountBundle\Tests\Entity;

use DigitalOceanAccountBundle\Entity\DigitalOceanConfig;
use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;

/**
 * @internal
 */
#[CoversClass(DigitalOceanConfig::class)]
final class DigitalOceanConfigTest extends AbstractEntityTestCase
{
    public function testConstructionWithDefaultValues(): void
    {
        $config = new DigitalOceanConfig();

        $this->assertEquals(0, $config->getId());
        $this->assertNull($config->getCreateTime());
        $this->assertNull($config->getUpdateTime());
        $this->assertNull($config->getRemark());
    }

    public function testGettersAndSettersWithValidValues(): void
    {
        $config = new DigitalOceanConfig();

        $apiKey = 'test-api-key-12345';
        $remark = 'Test configuration';
        $createTime = new \DateTimeImmutable('2023-01-01');
        $updateTime = new \DateTimeImmutable('2023-01-02');

        $config->setApiKey($apiKey);
        $config->setRemark($remark);

        $config->setCreateTime($createTime);
        $config->setUpdateTime($updateTime);

        $this->assertEquals($apiKey, $config->getApiKey());
        $this->assertEquals($remark, $config->getRemark());
        $this->assertEquals($createTime, $config->getCreateTime());
        $this->assertEquals($updateTime, $config->getUpdateTime());
    }

    public function testToPlainArrayReturnsCorrectFormat(): void
    {
        $config = new DigitalOceanConfig();

        $apiKey = 'test-api-key-12345';
        $remark = 'Test configuration';

        $config->setApiKey($apiKey);
        $config->setRemark($remark);

        $plainArray = $config->toPlainArray();
        $this->assertEquals(0, $plainArray['id']);
        $this->assertEquals($apiKey, $plainArray['apiKey']);
        $this->assertEquals($remark, $plainArray['remark']);
    }

    public function testToAdminArrayReturnsCorrectFormat(): void
    {
        $config = new DigitalOceanConfig();

        $apiKey = 'test-api-key-12345';
        $remark = 'Test configuration';

        $config->setApiKey($apiKey);
        $config->setRemark($remark);

        $adminArray = $config->toAdminArray();
        $this->assertEquals(0, $adminArray['id']);
        $this->assertEquals($apiKey, $adminArray['apiKey']);
        $this->assertEquals($remark, $adminArray['remark']);
    }

    protected function createEntity(): object
    {
        return new DigitalOceanConfig();
    }

    /**
     * @return iterable<array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        yield 'apiKey' => ['apiKey', 'test-api-key-12345'];
        yield 'remark' => ['remark', 'Test configuration'];
        yield 'createTime' => ['createTime', new \DateTimeImmutable('2023-01-01')];
        yield 'updateTime' => ['updateTime', new \DateTimeImmutable('2023-01-02')];
    }
}
