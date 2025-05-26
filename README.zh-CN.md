# DigitalOcean账户管理包

[![最新版本](https://img.shields.io/packagist/v/tourze/digital-ocean-account-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/digital-ocean-account-bundle)
[![构建状态](https://img.shields.io/travis/tourze/digital-ocean-account-bundle/master.svg?style=flat-square)](https://travis-ci.org/tourze/digital-ocean-account-bundle)
[![下载总量](https://img.shields.io/packagist/dt/tourze/digital-ocean-account-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/digital-ocean-account-bundle)

一个用于集成DigitalOcean API的Symfony包，提供账户管理、SSH密钥管理和配置服务。

[English](README.md) | [中文](README.zh-CN.md)

## 功能特性

- 完整的DigitalOcean API v2集成
- 账户信息管理和同步
- SSH密钥管理（创建、列表、删除）
- DigitalOcean API访问配置管理
- 集成Doctrine ORM实现数据持久化
- Symfony Bundle集成与自动装配支持

## 安装

```bash
composer require tourze/digital-ocean-account-bundle
```

在`config/bundles.php`文件中注册Bundle：

```php
<?php

return [
    // ...
    DigitalOceanAccountBundle\DigitalOceanAccountBundle::class => ['all' => true],
    // ...
];
```

## 快速开始

### 使用示例

#### 访问账户信息

```php
<?php

use DigitalOceanAccountBundle\Service\AccountService;

class YourController
{
    public function index(AccountService $accountService)
    {
        // 从DigitalOcean API获取账户信息
        $accountData = $accountService->getAccount();
        
        // 同步账户信息到数据库
        $account = $accountService->syncAccount();
        
        // 使用账户数据...
    }
}
```

## API文档

该包提供了几个用于与DigitalOcean API交互的服务：

- `AccountService`：账户信息管理
- `SSHKeyService`：SSH密钥管理
- `DigitalOceanConfigService`：API配置管理
- `DigitalOceanClient`：底层API客户端

每个服务都自动注册，可以在Symfony应用程序中自动装配使用。

## 贡献

欢迎贡献！请随时提交Pull Request。

## 许可证

MIT许可证。请查看[许可文件](LICENSE)了解更多信息。
