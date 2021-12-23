<?php

namespace App\Controller\Admin;

use App\Entity\TypeQuestion;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;


class TypeQuestionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TypeQuestion::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        $name =  TextField::new('name');
        // $description = TextField::new('description');
         $state = ChoiceField::new('state')->setChoices( [
             'ACTIVE' => 1,
             'INACTIVE' => 0,
         ],);
 
      
         if (Crud::PAGE_INDEX === $pageName) {
             return [$name,  $state];
         } else  if (Crud::PAGE_NEW === $pageName) {
             return [$name,  $state];
         } else  if (Crud::PAGE_EDIT === $pageName) {
             return [$name,  $state];
         } else  if (Crud::PAGE_DETAIL === $pageName) {
             return [$name,  $state];
         }
         
    }
    
}
