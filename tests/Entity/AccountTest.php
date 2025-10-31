<?php

namespace DigitalOceanAccountBundle\Tests\Entity;

use DigitalOceanAccountBundle\Entity\Account;
use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;

/**
 * @internal
 */
#[CoversClass(Account::class)]
final class AccountTest extends AbstractEntityTestCase
{
    public function testConstructionWithDefaultValues(): void
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

    public function testGettersAndSettersWithValidValues(): void
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
        $createTime = new \DateTimeImmutable('2023-01-01');
        $updateTime = new \DateTimeImmutable('2023-01-02');

        $account->setEmail($email);
        $account->setUuid($uuid);
        $account->setStatus($status);
        $account->setEmailVerified($emailVerified);
        $account->setTeamName($teamName);
        $account->setDropletLimit($dropletLimit);
        $account->setFloatingIpLimit($floatingIpLimit);
        $account->setReservedIpLimit($reservedIpLimit);
        $account->setVolumeLimit($volumeLimit);

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

    public function testToPlainArrayReturnsCorrectFormat(): void
    {
        $account = new Account();

        $email = 'test@example.com';
        $uuid = '12345678-1234-1234-1234-123456789012';
        $status = 'active';
        $emailVerified = true;

        $account->setEmail($email);
        $account->setUuid($uuid);
        $account->setStatus($status);
        $account->setEmailVerified($emailVerified);

        $plainArray = $account->toPlainArray();
        $this->assertEquals(0, $plainArray['id']);
        $this->assertEquals($email, $plainArray['email']);
        $this->assertEquals($uuid, $plainArray['uuid']);
        $this->assertEquals($status, $plainArray['status']);
        $this->assertEquals($emailVerified, $plainArray['emailVerified']);
    }

    public function testToAdminArrayReturnsCorrectFormat(): void
    {
        $account = new Account();

        $email = 'test@example.com';
        $uuid = '12345678-1234-1234-1234-123456789012';
        $status = 'active';
        $emailVerified = true;

        $account->setEmail($email);
        $account->setUuid($uuid);
        $account->setStatus($status);
        $account->setEmailVerified($emailVerified);

        $adminArray = $account->toAdminArray();
        $this->assertEquals(0, $adminArray['id']);
        $this->assertEquals($email, $adminArray['email']);
        $this->assertEquals($uuid, $adminArray['uuid']);
        $this->assertEquals($status, $adminArray['status']);
        $this->assertEquals($emailVerified, $adminArray['emailVerified']);
    }

    protected function createEntity(): object
    {
        return new Account();
    }

    /**
     * @return iterable<array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        yield 'email' => ['email', 'test@example.com'];
        yield 'uuid' => ['uuid', '12345678-1234-1234-1234-123456789012'];
        yield 'status' => ['status', 'active'];
        yield 'emailVerified' => ['emailVerified', true];
        yield 'teamName' => ['teamName', 'Test Team'];
        yield 'dropletLimit' => ['dropletLimit', '10'];
        yield 'floatingIpLimit' => ['floatingIpLimit', '5'];
        yield 'reservedIpLimit' => ['reservedIpLimit', '3'];
        yield 'volumeLimit' => ['volumeLimit', '20'];
        yield 'createTime' => ['createTime', new \DateTimeImmutable('2023-01-01')];
        yield 'updateTime' => ['updateTime', new \DateTimeImmutable('2023-01-02')];
    }
}
