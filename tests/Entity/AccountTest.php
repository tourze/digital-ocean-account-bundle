<?php

namespace DigitalOceanAccountBundle\Tests\Entity;

use DigitalOceanAccountBundle\Entity\Account;
use PHPUnit\Framework\TestCase;

class AccountTest extends TestCase
{
    public function testConstruction_withDefaultValues(): void
    {
        $account = new Account();
        
        $this->assertEquals(0, $account->getId());
        $this->assertNull($account->getCreateTime());
        $this->assertNull($account->getUpdateTime());
        $this->assertNull($account->getTeamName());
        $this->assertNull($account->getDropletLimit());
        $this->assertNull($account->getFloatingIpLimit());
        $this->assertNull($account->getReservedIpLimit());
        $this->assertNull($account->getVolumeLimit());
    }
    
    public function testGettersAndSetters_withValidValues(): void
    {
        $account = new Account();
        
        $email = 'test@example.com';
        $uuid = '12345678-1234-1234-1234-123456789012';
        $status = 'active';
        $emailVerified = true;
        $teamName = 'Test Team';
        $dropletLimit = '10';
        $floatingIpLimit = '5';
        $reservedIpLimit = '3';
        $volumeLimit = '20';
        $createTime = new \DateTime('2023-01-01');
        $updateTime = new \DateTime('2023-01-02');
        
        $account->setEmail($email)
            ->setUuid($uuid)
            ->setStatus($status)
            ->setEmailVerified($emailVerified)
            ->setTeamName($teamName)
            ->setDropletLimit($dropletLimit)
            ->setFloatingIpLimit($floatingIpLimit)
            ->setReservedIpLimit($reservedIpLimit)
            ->setVolumeLimit($volumeLimit);
        
        $account->setCreateTime($createTime);
        $account->setUpdateTime($updateTime);
        
        $this->assertEquals($email, $account->getEmail());
        $this->assertEquals($uuid, $account->getUuid());
        $this->assertEquals($status, $account->getStatus());
        $this->assertEquals($emailVerified, $account->getEmailVerified());
        $this->assertEquals($teamName, $account->getTeamName());
        $this->assertEquals($dropletLimit, $account->getDropletLimit());
        $this->assertEquals($floatingIpLimit, $account->getFloatingIpLimit());
        $this->assertEquals($reservedIpLimit, $account->getReservedIpLimit());
        $this->assertEquals($volumeLimit, $account->getVolumeLimit());
        $this->assertEquals($createTime, $account->getCreateTime());
        $this->assertEquals($updateTime, $account->getUpdateTime());
    }
    
    public function testToPlainArray_returnsCorrectFormat(): void
    {
        $account = new Account();
        
        $email = 'test@example.com';
        $uuid = '12345678-1234-1234-1234-123456789012';
        $status = 'active';
        $emailVerified = true;
        
        $account->setEmail($email)
            ->setUuid($uuid)
            ->setStatus($status)
            ->setEmailVerified($emailVerified);
        
        $plainArray = $account->toPlainArray();
        
        $this->assertIsArray($plainArray);
        $this->assertEquals(0, $plainArray['id']);
        $this->assertEquals($email, $plainArray['email']);
        $this->assertEquals($uuid, $plainArray['uuid']);
        $this->assertEquals($status, $plainArray['status']);
        $this->assertEquals($emailVerified, $plainArray['emailVerified']);
    }
    
    public function testToAdminArray_returnsCorrectFormat(): void
    {
        $account = new Account();
        
        $email = 'test@example.com';
        $uuid = '12345678-1234-1234-1234-123456789012';
        $status = 'active';
        $emailVerified = true;
        
        $account->setEmail($email)
            ->setUuid($uuid)
            ->setStatus($status)
            ->setEmailVerified($emailVerified);
        
        $adminArray = $account->toAdminArray();
        
        $this->assertIsArray($adminArray);
        $this->assertEquals(0, $adminArray['id']);
        $this->assertEquals($email, $adminArray['email']);
        $this->assertEquals($uuid, $adminArray['uuid']);
        $this->assertEquals($status, $adminArray['status']);
        $this->assertEquals($emailVerified, $adminArray['emailVerified']);
    }
} 