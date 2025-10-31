<?php

namespace DigitalOceanAccountBundle\DataFixtures;

use DigitalOceanAccountBundle\Entity\Account;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AccountFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $account = new Account();
        $account->setEmail('test@digitalocean.test');
        $account->setUuid('test-uuid-12345');
        $account->setStatus('active');
        $account->setEmailVerified(true);
        $account->setTeamName('Test Team');
        $account->setDropletLimit('10');
        $account->setFloatingIpLimit('5');
        $account->setReservedIpLimit('3');
        $account->setVolumeLimit('100');

        $manager->persist($account);
        $manager->flush();
    }
}
