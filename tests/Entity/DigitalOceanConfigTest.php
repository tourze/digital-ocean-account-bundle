<?php

namespace DigitalOceanAccountBundle\Tests\Entity;

use DigitalOceanAccountBundle\Entity\DigitalOceanConfig;
use PHPUnit\Framework\TestCase;

class DigitalOceanConfigTest extends TestCase
{
    public function testConstruction_withDefaultValues(): void
    {
        $config = new DigitalOceanConfig();

        $this->assertEquals(0, $config->getId());
        $this->assertNull($config->getCreateTime());
        $this->assertNull($config->getUpdateTime());
        $this->assertNull($config->getRemark());
    }

    public function testGettersAndSetters_withValidValues(): void
    {
        $config = new DigitalOceanConfig();

        $apiKey = 'test-api-key-12345';
        $remark = 'Test configuration';
        $createTime = new \DateTimeImmutable('2023-01-01');
        $updateTime = new \DateTimeImmutable('2023-01-02');

        $config->setApiKey($apiKey)
            ->setRemark($remark);

        $config->setCreateTime($createTime);
        $config->setUpdateTime($updateTime);

        $this->assertEquals($apiKey, $config->getApiKey());
        $this->assertEquals($remark, $config->getRemark());
        $this->assertEquals($createTime, $config->getCreateTime());
        $this->assertEquals($updateTime, $config->getUpdateTime());
    }

    public function testToPlainArray_returnsCorrectFormat(): void
    {
        $config = new DigitalOceanConfig();

        $apiKey = 'test-api-key-12345';
        $remark = 'Test configuration';

        $config->setApiKey($apiKey)
            ->setRemark($remark);

        $plainArray = $config->toPlainArray();
        $this->assertEquals(0, $plainArray['id']);
        $this->assertEquals($apiKey, $plainArray['apiKey']);
        $this->assertEquals($remark, $plainArray['remark']);
    }

    public function testToAdminArray_returnsCorrectFormat(): void
    {
        $config = new DigitalOceanConfig();

        $apiKey = 'test-api-key-12345';
        $remark = 'Test configuration';

        $config->setApiKey($apiKey)
            ->setRemark($remark);

        $adminArray = $config->toAdminArray();
        $this->assertEquals(0, $adminArray['id']);
        $this->assertEquals($apiKey, $adminArray['apiKey']);
        $this->assertEquals($remark, $adminArray['remark']);
    }
}
