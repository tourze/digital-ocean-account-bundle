<?php

namespace DigitalOceanAccountBundle;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use HttpClientBundle\HttpClientBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tourze\BundleDependency\BundleDependencyInterface;

class DigitalOceanAccountBundle extends Bundle implements BundleDependencyInterface
{
    public static function getBundleDependencies(): array
    {
        return [
            HttpClientBundle::class => ['all' => true],
            DoctrineBundle::class => ['all' => true],
        ];
    }
}
