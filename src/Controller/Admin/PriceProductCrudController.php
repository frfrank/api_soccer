<?php

namespace App\Controller\Admin;

use App\Entity\PriceProduct;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

class PriceProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PriceProduct::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [         
            
            TextField::new('description'),
            ChoiceField::new('state')->setChoices( [
                'ACTIVE' => 1,
                'INACTIVE' => 0,
            ],),
            AssociationField::new('product'),
            MoneyField::new('price')->setCurrency('EUR'),
            
            
        ];
    }
}
