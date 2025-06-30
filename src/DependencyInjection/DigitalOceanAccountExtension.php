<?php

namespace DigitalOceanAccountBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class DigitalOceanAccountExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $container->setParameter('digital_ocean_account.resource_dir', realpath(__DIR__ . '/../Resources/config'));
        
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.yaml');
    }
}
