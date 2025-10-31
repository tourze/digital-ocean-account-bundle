<?php

declare(strict_types=1);

namespace DigitalOceanAccountBundle\Tests\Helper;

use DigitalOceanAccountBundle\Entity\DigitalOceanConfig;

/**
 * 测试用的DigitalOcean配置服务
 *
 * @internal
 */
final class TestDigitalOceanConfigService
{
    private ?DigitalOceanConfig $config = null;

    /**
     * 设置配置
     */
    public function setConfig(?DigitalOceanConfig $config): void
    {
        $this->config = $config;
    }

    /**
     * 创建带有配置的新实例
     */
    public function createWithConfig(DigitalOceanConfig $config): self
    {
        $instance = new self();
        $instance->setConfig($config);

        return $instance;
    }

    /**
     * 获取配置
     */
    public function getConfig(): ?DigitalOceanConfig
    {
        return $this->config;
    }

    /**
     * 模拟保存配置
     */
    public function saveConfig(string $apiKey, ?string $remark = null): DigitalOceanConfig
    {
        if (null === $this->config) {
            $this->config = new DigitalOceanConfig();
        }

        $this->config->setApiKey($apiKey);

        if (null !== $remark) {
            $this->config->setRemark($remark);
        }

        return $this->config;
    }
}
