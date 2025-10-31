<?php

namespace DigitalOceanAccountBundle\Entity;

use DigitalOceanAccountBundle\Repository\AccountRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\Arrayable\PlainArrayInterface;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;

/**
 * @implements PlainArrayInterface<string, mixed>
 * @implements AdminArrayInterface<string, mixed>
 */
#[ORM\Entity(repositoryClass: AccountRepository::class)]
#[ORM\Table(name: 'ims_digital_ocean_account', options: ['comment' => 'DigitalOcean账号信息'])]
class Account implements PlainArrayInterface, AdminArrayInterface, \Stringable
{
    use TimestampableAware;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->email;
    }

    #[ORM\Column(type: Types::STRING, length: 255, options: ['comment' => '邮箱'])]
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\Length(max: 255)]
    private string $email;

    #[ORM\Column(type: Types::STRING, length: 255, options: ['comment' => '用户UUID'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private string $uuid;

    #[ORM\Column(type: Types::STRING, length: 255, options: ['comment' => '用户状态'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private string $status;

    #[ORM\Column(type: Types::BOOLEAN, options: ['comment' => '是否验证'])]
    #[Assert\NotNull]
    private bool $emailVerified;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '团队名称'])]
    #[Assert\Length(max: 255)]
    private ?string $teamName = null;

    #[ORM\Column(type: Types::STRING, length: 20, nullable: true, options: ['comment' => '下拉菜单展示'])]
    #[Assert\Length(max: 20)]
    private ?string $dropletLimit = null;

    #[ORM\Column(type: Types::STRING, length: 20, nullable: true, options: ['comment' => '浮动IP限制'])]
    #[Assert\Length(max: 20)]
    private ?string $floatingIpLimit = null;

    #[ORM\Column(type: Types::STRING, length: 20, nullable: true, options: ['comment' => '预留IP限制'])]
    #[Assert\Length(max: 20)]
    private ?string $reservedIpLimit = null;

    #[ORM\Column(type: Types::STRING, length: 20, nullable: true, options: ['comment' => '卷限制'])]
    #[Assert\Length(max: 20)]
    private ?string $volumeLimit = null;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getEmailVerified(): bool
    {
        return $this->emailVerified;
    }

    public function setEmailVerified(bool $emailVerified): void
    {
        $this->emailVerified = $emailVerified;
    }

    public function getTeamName(): ?string
    {
        return $this->teamName;
    }

    public function setTeamName(?string $teamName): void
    {
        $this->teamName = $teamName;
    }

    public function getDropletLimit(): ?string
    {
        return $this->dropletLimit;
    }

    public function setDropletLimit(?string $dropletLimit): void
    {
        $this->dropletLimit = $dropletLimit;
    }

    public function getFloatingIpLimit(): ?string
    {
        return $this->floatingIpLimit;
    }

    public function setFloatingIpLimit(?string $floatingIpLimit): void
    {
        $this->floatingIpLimit = $floatingIpLimit;
    }

    public function getReservedIpLimit(): ?string
    {
        return $this->reservedIpLimit;
    }

    public function setReservedIpLimit(?string $reservedIpLimit): void
    {
        $this->reservedIpLimit = $reservedIpLimit;
    }

    public function getVolumeLimit(): ?string
    {
        return $this->volumeLimit;
    }

    public function setVolumeLimit(?string $volumeLimit): void
    {
        $this->volumeLimit = $volumeLimit;
    }

    /**
     * @return array<string, mixed>
     */
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

    /**
     * @return array<string, mixed>
     */
    public function toAdminArray(): array
    {
        return $this->toPlainArray();
    }

    /**
     * @return array<string, mixed>
     */
    public function retrievePlainArray(): array
    {
        return $this->toPlainArray();
    }

    /**
     * @return array<string, mixed>
     */
    public function retrieveAdminArray(): array
    {
        return $this->toAdminArray();
    }
}
