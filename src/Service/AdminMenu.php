<?php

declare(strict_types=1);

namespace DigitalOceanAccountBundle\Service;

use DigitalOceanAccountBundle\Entity\Account;
use DigitalOceanAccountBundle\Entity\DigitalOceanConfig;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;
use Tourze\EasyAdminMenuBundle\Service\MenuProviderInterface;

#[Autoconfigure(public: true)]
readonly class AdminMenu implements MenuProviderInterface
{
    public function __construct(private LinkGeneratorInterface $linkGenerator)
    {
    }

    public function __invoke(ItemInterface $item): void
    {
        $cloudServiceMenu = $item->getChild('云服务管理');
        if (null === $cloudServiceMenu) {
            $cloudServiceMenu = $item->addChild('云服务管理');
        }

        $digitalOceanMenu = $cloudServiceMenu
            ->addChild('DigitalOcean')
            ->setAttribute('icon', 'fas fa-cloud')
        ;

        $digitalOceanMenu
            ->addChild('账号管理')
            ->setUri($this->linkGenerator->getCurdListPage(Account::class))
            ->setAttribute('icon', 'fas fa-user-circle')
        ;

        $digitalOceanMenu
            ->addChild('配置管理')
            ->setUri($this->linkGenerator->getCurdListPage(DigitalOceanConfig::class))
            ->setAttribute('icon', 'fas fa-cog')
        ;
    }
}
