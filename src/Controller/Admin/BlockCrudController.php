<?php

namespace App\Controller\Admin;

use App\Entity\Block;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

class BlockCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Block::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Bloque')
            ->setEntityLabelInPlural('Bloques')
            ->setSearchFields(['title']);
    }


    public function configureFields(string $pageName): iterable
    {
        $title = TextField::new('title')
            ->setLabel('Nombre')
            ->setColumns('col-md-6 col-xl-4');

        $icon = TextField::new('icon')
            ->setLabel('Icon')
            ->setColumns('col-md-6 col-xl-4');

        $orden = NumberField::new('orden')
            ->setLabel('Orden')
            ->setColumns('col-md-6 col-xl-4');


        $createdAt   = DateTimeField::new('createdAt');
        $updatedAt   = DateTimeField::new('updatedAt');
        $createdBy   = AssociationField::new('createdBy');
        $updatedBy   = AssociationField::new('updatedBy');

        $field = [];

        if (Crud::PAGE_INDEX === $pageName) {
            $field = [$title, $icon,  $orden, $createdAt];
        } else  if (in_array($pageName, [Crud::PAGE_NEW, Crud::PAGE_EDIT])) {
            $field = [$title, $icon, $orden];
        } else if (Crud::PAGE_DETAIL === $pageName) {
            $field = [$title, $icon, $createdAt, $updatedAt, $createdBy, $updatedBy];
        }

        return $field;
    }
}
