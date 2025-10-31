<?php

namespace DigitalOceanAccountBundle\Entity;

use DigitalOceanAccountBundle\Repository\DigitalOceanConfigRepository;
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
#[ORM\Entity(repositoryClass: DigitalOceanConfigRepository::class)]
#[ORM\Table(name: 'ims_digital_ocean_config', options: ['comment' => 'DigitalOcean配置'])]
class DigitalOceanConfig implements PlainArrayInterface, AdminArrayInterface, \Stringable
{
    use TimestampableAware;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255, options: ['comment' => 'API Key'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private string $apiKey;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '备注'])]
    #[Assert\Length(max: 255)]
    private ?string $remark = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->remark ?? 'DigitalOcean配置#' . $this->id;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    public function getRemark(): ?string
    {
        return $this->remark;
    }

    public function setRemark(?string $remark): void
    {
        $this->remark = $remark;
    }

    /**
     * @return array<string, mixed>
     */
    public function toPlainArray(): array
    {
        return [
            'id' => $this->getId(),
            'apiKey' => $this->getApiKey(),
            'remark' => $this->getRemark(),
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
