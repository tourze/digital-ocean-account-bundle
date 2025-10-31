# DigitalOcean账户管理包

[![PHP](https://img.shields.io/badge/PHP-8.1%2B-blue.svg?style=flat-square)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green.svg?style=flat-square)](LICENSE)
[![最新版本](https://img.shields.io/packagist/v/tourze/digital-ocean-account-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/digital-ocean-account-bundle)
[![构建状态](https://img.shields.io/travis/tourze/digital-ocean-account-bundle/master.svg?style=flat-square)](https://travis-ci.org/tourze/digital-ocean-account-bundle)
[![下载总量](https://img.shields.io/packagist/dt/tourze/digital-ocean-account-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/digital-ocean-account-bundle)
[![代码覆盖率](https://img.shields.io/codecov/c/github/tourze/digital-ocean-account-bundle/master.svg?style=flat-square)](https://codecov.io/gh/tourze/digital-ocean-account-bundle)

一个用于集成DigitalOcean API的Symfony包，提供账户管理、SSH密钥管理和配置服务。

[English](README.md) | [中文](README.zh-CN.md)

## 目录

- [功能特性](#功能特性)
- [依赖关系](#依赖关系)
- [安装](#安装)
- [配置](#配置)
- [快速开始](#快速开始)
- [API文档](#api文档)
- [高级用法](#高级用法)
- [贡献](#贡献)
- [许可证](#许可证)

## 功能特性

- DigitalOcean API v2集成
- 账户信息管理和同步
- DigitalOcean API访问配置管理
- 集成Doctrine ORM实现数据持久化
- Symfony Bundle集成与自动装配支持

## 依赖关系

此包需要以下组件：

- PHP 8.1 或更高版本
- Symfony 6.4 或更高版本
- Doctrine ORM 3.0 或更高版本
- HTTP Client Bundle 用于API通信

完整的依赖列表请查看 `composer.json` 文件。

## 安装

通过 Composer 安装此包：

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

## 配置

### 数据库设置

运行以下命令创建必要的数据库表：

```bash
php bin/console doctrine:migrations:migrate
```

### API配置

在数据库中创建一个DigitalOcean配置记录，包含您的API密钥：

```php
// 在控制器或服务中
$config = new DigitalOceanConfig();
$config->setApiKey('your-digitalocean-api-key');
$config->setRemark('生产环境API密钥');

$this->entityManager->persist($config);
$this->entityManager->flush();
```

### Bundle配置

该Bundle会自动注册所有服务。基本使用无需额外配置。

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

#### 管理配置

```php
<?php

use DigitalOceanAccountBundle\Service\DigitalOceanConfigService;

class ConfigController
{
    public function setup(DigitalOceanConfigService $configService)
    {
        // 获取当前配置
        $config = $configService->getConfig();
        
        // 服务将自动使用配置的API密钥
        // 用于所有DigitalOcean API请求
    }
}
```

## API文档

该包提供了几个用于与DigitalOcean API交互的服务：

### AccountService

- `getAccount()`：从DigitalOcean API获取账户信息
- `syncAccount()`：同步账户信息到数据库

### DigitalOceanConfigService

- `getConfig()`：获取当前的DigitalOcean API配置

### DigitalOceanClient

- `request(DigitalOceanRequest $request)`：向DigitalOcean API发送请求

每个服务都自动注册，可以在Symfony应用程序中自动装配使用。

## 高级用法

### 自定义请求类型

您可以通过扩展 `DigitalOceanRequest` 类来创建自定义请求类型：

```php
<?php

use DigitalOceanAccountBundle\Request\DigitalOceanRequest;

class CustomRequest extends DigitalOceanRequest
{
    protected function getEndpoint(): string
    {
        return '/v2/custom-endpoint';
    }
    
    protected function getMethod(): string
    {
        return 'GET';
    }
}
```

### 错误处理

该包为不同的错误场景提供了自定义异常：

```php
<?php

use DigitalOceanAccountBundle\Exception\DigitalOceanException;
use DigitalOceanAccountBundle\Exception\MissingApiKeyException;

try {
    $account = $accountService->syncAccount();
} catch (MissingApiKeyException $e) {
    // 处理缺少API密钥的情况
} catch (DigitalOceanException $e) {
    // 处理其他DigitalOcean相关错误
}
```

### 测试

该包包含全面的测试覆盖。运行测试：

```bash
vendor/bin/phpunit packages/digital-ocean-account-bundle/tests
```

## 贡献

欢迎贡献！请随时提交Pull Request。

1. Fork 仓库
2. 创建功能分支
3. 进行更改
4. 为您的更改添加测试
5. 提交pull request

## 许可证

MIT许可证。请查看[许可文件](LICENSE)了解更多信息。