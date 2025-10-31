<?php

namespace DigitalOceanAccountBundle\DataFixtures;

use DigitalOceanAccountBundle\Entity\DigitalOceanConfig;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DigitalOceanConfigFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $config = new DigitalOceanConfig();
        $config->setApiKey('test-api-key-12345');
        $config->setRemark('测试用DigitalOcean配置');

        $manager->persist($config);
        $manager->flush();
    }
}
