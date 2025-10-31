<?php

declare(strict_types=1);

namespace DigitalOceanAccountBundle\Controller\Admin;

use DigitalOceanAccountBundle\Entity\DigitalOceanConfig;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;

#[AdminCrud(
    routePath: '/digital-ocean/config',
    routeName: 'digital_ocean_config'
)]
final class DigitalOceanConfigCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DigitalOceanConfig::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('DigitalOcean配置')
            ->setEntityLabelInPlural('DigitalOcean配置管理')
            ->setPageTitle(Crud::PAGE_INDEX, 'DigitalOcean配置列表')
            ->setPageTitle(Crud::PAGE_NEW, '新建DigitalOcean配置')
            ->setPageTitle(Crud::PAGE_EDIT, '编辑DigitalOcean配置')
            ->setPageTitle(Crud::PAGE_DETAIL, 'DigitalOcean配置详情')
            ->setDefaultSort(['createTime' => 'DESC'])
            ->setSearchFields(['remark'])
            ->showEntityActionsInlined()
            ->setFormThemes(['@EasyAdmin/crud/form_theme.html.twig'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID')
            ->onlyOnIndex()
        ;

        yield TextField::new('apiKey', 'API Key')
            ->setColumns('col-md-8')
            ->setRequired(true)
            ->setHelp('请输入有效的DigitalOcean API Key')
        ;

        yield TextField::new('remark', '备注')
            ->setColumns('col-md-4')
            ->setRequired(false)
            ->setHelp('可选的备注信息，便于区分不同配置')
        ;

        yield DateTimeField::new('createTime', '创建时间')
            ->hideOnForm()
            ->setFormat('yyyy-MM-dd HH:mm:ss')
        ;

        yield DateTimeField::new('updateTime', '更新时间')
            ->onlyOnDetail()
            ->setFormat('yyyy-MM-dd HH:mm:ss')
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('remark')
            ->add(DateTimeFilter::new('createTime'))
            ->add(DateTimeFilter::new('updateTime'))
        ;
    }
}
