# DigitalOcean Account Bundle

[![Latest Version](https://img.shields.io/packagist/v/tourze/digital-ocean-account-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/digital-ocean-account-bundle)
[![Build Status](https://img.shields.io/travis/tourze/digital-ocean-account-bundle/master.svg?style=flat-square)](https://travis-ci.org/tourze/digital-ocean-account-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/digital-ocean-account-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/digital-ocean-account-bundle)

A Symfony bundle for integrating with DigitalOcean API, providing account management, SSH key management, and configuration services.

[English](README.md) | [中文](README.zh-CN.md)

## Features

- Complete DigitalOcean API v2 integration
- Account information management and synchronization
- SSH key management (create, list, delete)
- Configuration management for DigitalOcean API access
- Doctrine ORM integration for data persistence
- Symfony Bundle integration with autowiring support

## Installation

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

## Quick Start

### Configuration

Create a configuration file at `config/packages/digital_ocean_account.yaml`:

```yaml
digital_ocean_account:
    api_key: '%env(DIGITAL_OCEAN_API_KEY)%'
```

Set your DigitalOcean API key in your `.env` file:

```
DIGITAL_OCEAN_API_KEY=your_digital_ocean_api_key
```

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

#### Managing SSH Keys

```php
<?php

use DigitalOceanAccountBundle\Service\SSHKeyService;

class YourController
{
    public function manageSshKeys(SSHKeyService $sshKeyService)
    {
        // List all SSH keys
        $keys = $sshKeyService->listKeys();
        
        // Create a new SSH key
        $newKey = $sshKeyService->createKey('my-key-name', 'ssh-rsa AAAA...');
        
        // Delete an SSH key
        $sshKeyService->deleteKey($keyId);
    }
}
```

## API Documentation

The bundle provides several services for interacting with the DigitalOcean API:

- `AccountService`: Account information management
- `SSHKeyService`: SSH key management
- `DigitalOceanConfigService`: API configuration management
- `DigitalOceanClient`: Low-level API client

Each service is automatically registered and can be autowired in your Symfony application.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
