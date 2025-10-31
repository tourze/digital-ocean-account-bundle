<?php

declare(strict_types=1);

namespace DigitalOceanAccountBundle\Controller\Admin;

use DigitalOceanAccountBundle\Entity\Account;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;

#[AdminCrud(
    routePath: '/digital-ocean/account',
    routeName: 'digital_ocean_account'
)]
final class AccountCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Account::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('DigitalOcean账号')
            ->setEntityLabelInPlural('DigitalOcean账号管理')
            ->setPageTitle(Crud::PAGE_INDEX, 'DigitalOcean账号列表')
            ->setPageTitle(Crud::PAGE_NEW, '新建DigitalOcean账号')
            ->setPageTitle(Crud::PAGE_EDIT, '编辑DigitalOcean账号')
            ->setPageTitle(Crud::PAGE_DETAIL, 'DigitalOcean账号详情')
            ->setDefaultSort(['createTime' => 'DESC'])
            ->setSearchFields(['email', 'uuid', 'teamName', 'status'])
            ->showEntityActionsInlined()
            ->setFormThemes(['@EasyAdmin/crud/form_theme.html.twig'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID')
            ->onlyOnIndex()
        ;

        yield EmailField::new('email', '邮箱')
            ->setColumns('col-md-6')
            ->setRequired(true)
        ;

        yield TextField::new('uuid', '用户UUID')
            ->setColumns('col-md-6')
            ->setRequired(true)
        ;

        yield TextField::new('status', '用户状态')
            ->setColumns('col-md-6')
            ->setRequired(true)
        ;

        yield BooleanField::new('emailVerified', '是否验证')
            ->renderAsSwitch(false)
            ->setColumns('col-md-6')
        ;

        yield TextField::new('teamName', '团队名称')
            ->setColumns('col-md-6')
            ->setRequired(false)
        ;

        yield TextField::new('dropletLimit', '下拉菜单展示')
            ->setColumns('col-md-6')
            ->setRequired(false)
            ->onlyOnDetail()
        ;

        yield TextField::new('floatingIpLimit', '浮动IP限制')
            ->setColumns('col-md-6')
            ->setRequired(false)
            ->onlyOnDetail()
        ;

        yield TextField::new('reservedIpLimit', '预留IP限制')
            ->setColumns('col-md-6')
            ->setRequired(false)
            ->onlyOnDetail()
        ;

        yield TextField::new('volumeLimit', '卷限制')
            ->setColumns('col-md-6')
            ->setRequired(false)
            ->onlyOnDetail()
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
            ->add('email')
            ->add('status')
            ->add(BooleanFilter::new('emailVerified'))
            ->add('teamName')
            ->add(DateTimeFilter::new('createTime'))
            ->add(DateTimeFilter::new('updateTime'))
        ;
    }
}
