# DigitalOcean Account Bundle

[![PHP](https://img.shields.io/badge/PHP-8.1%2B-blue.svg?style=flat-square)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green.svg?style=flat-square)](LICENSE)
[![Latest Version](https://img.shields.io/packagist/v/tourze/digital-ocean-account-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/digital-ocean-account-bundle)
[![Build Status](https://img.shields.io/travis/tourze/digital-ocean-account-bundle/master.svg?style=flat-square)](https://travis-ci.org/tourze/digital-ocean-account-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/digital-ocean-account-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/digital-ocean-account-bundle)
[![Code Coverage](https://img.shields.io/codecov/c/github/tourze/digital-ocean-account-bundle/master.svg?style=flat-square)](https://codecov.io/gh/tourze/digital-ocean-account-bundle)

A Symfony bundle for integrating with DigitalOcean API, providing account management, 
SSH key management, and configuration services.

[English](README.md) | [中文](README.zh-CN.md)

## Table of Contents

- [Features](#features)
- [Dependencies](#dependencies)
- [Installation](#installation)
- [Configuration](#configuration)
- [Quick Start](#quick-start)
- [API Documentation](#api-documentation)
- [Advanced Usage](#advanced-usage)
- [Contributing](#contributing)
- [License](#license)

## Features

- DigitalOcean API v2 integration
- Account information management and synchronization
- Configuration management for DigitalOcean API access
- Doctrine ORM integration for data persistence
- Symfony Bundle integration with autowiring support

## Dependencies

This bundle requires the following components:

- PHP 8.1 or higher
- Symfony 6.4 or higher
- Doctrine ORM 3.0 or higher
- HTTP Client Bundle for API communication

For a complete list of dependencies, please see the `composer.json` file.

## Installation

Install the bundle via Composer:

```bash
composer require tourze/digital-ocean-account-bundle
```

Register the bundle in your `config/bundles.php` file:

```php
<?php

return [
    // ...
    DigitalOceanAccountBundle\DigitalOceanAccountBundle::class => ['all' => true],
    // ...
];
```

## Configuration

### Database Setup

Run the following command to create the necessary database tables:

```bash
php bin/console doctrine:migrations:migrate
```

### API Configuration

Create a DigitalOcean configuration record in your database with your API key:

```php
// In a controller or service
$config = new DigitalOceanConfig();
$config->setApiKey('your-digitalocean-api-key');
$config->setRemark('Production API Key');

$this->entityManager->persist($config);
$this->entityManager->flush();
```

### Bundle Configuration

The bundle automatically registers all services. No additional configuration 
is required for basic usage.

## Quick Start

### Usage Examples

#### Accessing Account Information

```php
<?php

use DigitalOceanAccountBundle\Service\AccountService;

class YourController
{
    public function index(AccountService $accountService)
    {
        // Get account information from DigitalOcean API
        $accountData = $accountService->getAccount();
        
        // Synchronize account information to database
        $account = $accountService->syncAccount();
        
        // Use account data...
    }
}
```

#### Managing Configuration

```php
<?php

use DigitalOceanAccountBundle\Service\DigitalOceanConfigService;

class ConfigController
{
    public function setup(DigitalOceanConfigService $configService)
    {
        // Get the current configuration
        $config = $configService->getConfig();
        
        // The service will automatically use the configured API key
        // for all DigitalOcean API requests
    }
}
```

## API Documentation

The bundle provides several services for interacting with the DigitalOcean API:

### AccountService

- `getAccount()`: Retrieve account information from DigitalOcean API
- `syncAccount()`: Synchronize account information to database

### DigitalOceanConfigService

- `getConfig()`: Get the current DigitalOcean API configuration

### DigitalOceanClient

- `request(DigitalOceanRequest $request)`: Send requests to DigitalOcean API

Each service is automatically registered and can be autowired in your 
Symfony application.

## Advanced Usage

### Custom Request Types

You can create custom request types by extending the `DigitalOceanRequest` class:

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

### Error Handling

The bundle provides custom exceptions for different error scenarios:

```php
<?php

use DigitalOceanAccountBundle\Exception\DigitalOceanException;
use DigitalOceanAccountBundle\Exception\MissingApiKeyException;

try {
    $account = $accountService->syncAccount();
} catch (MissingApiKeyException $e) {
    // Handle missing API key
} catch (DigitalOceanException $e) {
    // Handle other DigitalOcean-related errors
}
```

### Testing

The bundle includes comprehensive test coverage. Run tests with:

```bash
vendor/bin/phpunit packages/digital-ocean-account-bundle/tests
```

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for your changes
5. Submit a pull request

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.