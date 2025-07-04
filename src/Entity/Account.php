<?php

namespace DigitalOceanAccountBundle\Entity;

use DigitalOceanAccountBundle\Repository\AccountRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\Arrayable\PlainArrayInterface;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
#[ORM\Table(name: 'ims_digital_ocean_account', options: ['comment' => 'DigitalOcean账号信息'])]
class Account implements PlainArrayInterface, AdminArrayInterface, \Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private ?int $id = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->email;
    }

    #[ORM\Column(type: Types::STRING, length: 255, options: ['comment' => '邮箱'])]
    private string $email;

    #[ORM\Column(type: Types::STRING, length: 255, options: ['comment' => '用户UUID'])]
    private string $uuid;

    #[ORM\Column(type: Types::STRING, length: 255, options: ['comment' => '用户状态'])]
    private string $status;

    #[ORM\Column(type: Types::BOOLEAN, options: ['comment' => '是否验证'])]
    private bool $emailVerified;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '团队名称'])]
    private ?string $teamName = null;

    #[ORM\Column(type: Types::STRING, length: 20, nullable: true, options: ['comment' => '下拉菜单展示'])]
    private ?string $dropletLimit = null;

    #[ORM\Column(type: Types::STRING, length: 20, nullable: true, options: ['comment' => '浮动IP限制'])]
    private ?string $floatingIpLimit = null;

    #[ORM\Column(type: Types::STRING, length: 20, nullable: true, options: ['comment' => '预留IP限制'])]
    private ?string $reservedIpLimit = null;

    #[ORM\Column(type: Types::STRING, length: 20, nullable: true, options: ['comment' => '卷限制'])]
    private ?string $volumeLimit = null;

    use TimestampableAware;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getEmailVerified(): bool
    {
        return $this->emailVerified;
    }

    public function setEmailVerified(bool $emailVerified): self
    {
        $this->emailVerified = $emailVerified;
        return $this;
    }

    public function getTeamName(): ?string
    {
        return $this->teamName;
    }

    public function setTeamName(?string $teamName): self
    {
        $this->teamName = $teamName;
        return $this;
    }

    public function getDropletLimit(): ?string
    {
        return $this->dropletLimit;
    }

    public function setDropletLimit(?string $dropletLimit): self
    {
        $this->dropletLimit = $dropletLimit;
        return $this;
    }

    public function getFloatingIpLimit(): ?string
    {
        return $this->floatingIpLimit;
    }

    public function setFloatingIpLimit(?string $floatingIpLimit): self
    {
        $this->floatingIpLimit = $floatingIpLimit;
        return $this;
    }

    public function getReservedIpLimit(): ?string
    {
        return $this->reservedIpLimit;
    }

    public function setReservedIpLimit(?string $reservedIpLimit): self
    {
        $this->reservedIpLimit = $reservedIpLimit;
        return $this;
    }

    public function getVolumeLimit(): ?string
    {
        return $this->volumeLimit;
    }

    public function setVolumeLimit(?string $volumeLimit): self
    {
        $this->volumeLimit = $volumeLimit;
        return $this;
    }

    public function toPlainArray(): array
    {
        return [
            'id' => $this->getId(),
            'email' => $this->getEmail(),
            'uuid' => $this->getUuid(),
            'status' => $this->getStatus(),
            'emailVerified' => $this->getEmailVerified(),
            'teamName' => $this->getTeamName(),
            'dropletLimit' => $this->getDropletLimit(),
            'floatingIpLimit' => $this->getFloatingIpLimit(),
            'reservedIpLimit' => $this->getReservedIpLimit(),
            'volumeLimit' => $this->getVolumeLimit(),
            'createTime' => $this->getCreateTime()?->format('Y-m-d H:i:s'),
            'updateTime' => $this->getUpdateTime()?->format('Y-m-d H:i:s'),
        ];
    }

    public function toAdminArray(): array
    {
        return $this->toPlainArray();
    }

    public function retrievePlainArray(): array
    {
        return $this->toPlainArray();
    }

    public function retrieveAdminArray(): array
    {
        return $this->toAdminArray();
    }
}
