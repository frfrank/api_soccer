<?php

namespace App\Controller\Admin;

use App\Entity\Blocks;
use Doctrine\Migrations\Version\State;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use Symfony\Contracts\Translation\TranslatorInterface;


class BlocksCrudController extends CrudController
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
        return Blocks::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular($this->trans('blocks.labelInSingular'))
            ->setEntityLabelInPlural($this->trans('blocks.labelInPlural'))
            ->setSearchFields(['name']);
    }


    public function configureFields(string $pageName): iterable
    {
        $name = TextField::new('name')
            ->setLabel($this->trans('Name'))
            ->setColumns('col-md-6 col-xl-6');

        $icon = TextField::new('icon')
            ->setLabel($this->trans('blocks.configureFields.icon'))
            ->setColumns('col-md-6 col-xl-6');

        $state = BooleanField::new('state');

       

        $panelBlock  = FormField::addPanel($this->trans('blocks.panel.company'));        


        $createdAt   = DateTimeField::new('createdAt');
        $updatedAt   = DateTimeField::new('updatedAt');
        $createdBy   = AssociationField::new('createdBy');
        $updatedBy   = AssociationField::new('updatedBy');

        $field = [];

        if (Crud::PAGE_INDEX === $pageName) {
            $field = [$name, $icon, $state, $createdAt];
        } else  if (in_array($pageName, [Crud::PAGE_NEW, Crud::PAGE_EDIT])) {
            $field = [$panelBlock,$name, $icon];
        } else if (Crud::PAGE_DETAIL === $pageName) {
            $field = [$name, $createdAt, $updatedAt, $createdBy, $updatedBy];
        }

        return $field;
    }
}
