<?php

namespace App\Controller\Admin;

use App\Entity\Company;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use Symfony\Contracts\Translation\TranslatorInterface;


class CompanyCrudController extends CrudController
{
    public function __construct(
        ParameterBagInterface $params,
        EntityManagerInterface $em,
        AdminContextProvider $context,
        ValidatorInterface $validator,
        TranslatorInterface $translator,
        AdminUrlGenerator $adminUrlGenerator
    ) {
        parent::__construct($params, $em, $context, $validator, $translator, $adminUrlGenerator);
    }

    public static function getEntityFqcn(): string
    {
        return Company::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular($this->trans('company.labelInSingular'))
            ->setEntityLabelInPlural($this->trans('company.labelInPlural'))
            ->setSearchFields(['name']);
    }



    public function configureFields(string $pageName): iterable
    {
        $name = TextField::new('name')
            ->setLabel($this->trans('company.configureFields.name'))
            ->setColumns('col-md-6 col-xl-6');

        $email = EmailField::new('email')
            ->setLabel($this->trans('company.configureFields.email'))
            ->setColumns('col-md-6 col-xl-6');

        $url = TextField::new('url')
            ->setLabel($this->trans('company.configureFields.url'))
            ->setColumns('col-md-6 col-xl-6');

        $domain = TextField::new('domain')
            ->setLabel($this->trans('company.configureFields.domain'))
            ->setColumns('col-md-6 col-xl-6');

        $panelCompany  = FormField::addPanel($this->trans('company.panel.company'));
        


        $createdAt   = DateTimeField::new('createdAt');
        $updatedAt   = DateTimeField::new('updatedAt');
        $createdBy   = AssociationField::new('createdBy');
        $updatedBy   = AssociationField::new('updatedBy');

        $field = [];

        if (Crud::PAGE_INDEX === $pageName) {
            $field = [$name, $email, $createdAt];
        } else  if (in_array($pageName, [Crud::PAGE_NEW, Crud::PAGE_EDIT])) {
            $field = [$panelCompany,$name, $email, $url, $domain];
        } else if (Crud::PAGE_DETAIL === $pageName) {
            $field = [$name, $email, $url, $domain, $createdAt, $updatedAt, $createdBy, $updatedBy];
        }

        return $field;
    }
}
