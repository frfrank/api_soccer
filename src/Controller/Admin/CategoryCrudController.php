<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use Vich\UploaderBundle\Form\Type\VichFileType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


class CategoryCrudController extends AbstractCrudController
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    
    public function configureFields(string $pageName): iterable
    {     
        
        $name =  TextField::new('name');
       // $description = TextField::new('description');
        $state = ChoiceField::new('state')->setChoices( [
            'ACTIVE' => 1,
            'INACTIVE' => 0,
        ],);

        $image = Field::new('imageFile')->setFormType(VichFileType::class)->setCustomOptions([])->setFormTypeOptions([
            'attr' => [
                'accept' => 'image/*'
            ]
        ]);
        $imageFile = ImageField::new('image')->setBasePath($this->params->get('app.path.category_images'));
        if (Crud::PAGE_INDEX === $pageName) {
            return [$name,  $state, $imageFile];
        } else  if (Crud::PAGE_NEW === $pageName) {
            return [$name,  $state, $image];
        } else  if (Crud::PAGE_EDIT === $pageName) {
            return [$name,  $state, $image];
        } else  if (Crud::PAGE_DETAIL === $pageName) {
            return [$name,  $state, $imageFile];
        }
        
    }
    
}
