<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Vich\UploaderBundle\Form\Type\VichFileType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\CrudDto;




class ProductCrudController extends AbstractCrudController
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public static function getEntityFqcn(): string
    {
        return Product::class;
    }


    public function configureFields(string $pageName): iterable
    {
        $name =  TextField::new('name');
        $description = TextField::new('description');
        $category =  AssociationField::new('Category');
        $image = Field::new('imageFile')->setFormType(VichFileType::class)->setCustomOptions([])->setFormTypeOptions([
            'attr' => [
                'accept' => 'image/*'
            ]
        ]);
        $imageFile = ImageField::new('image')->setBasePath($this->params->get('app.path.product_images'));
        if (Crud::PAGE_INDEX === $pageName) {
            return [$name, $description, $category, $imageFile];
        } else  if (Crud::PAGE_NEW === $pageName) {
            return [$name, $description, $category, $image];
        } else  if (Crud::PAGE_EDIT === $pageName) {
            return [$name, $description, $category, $image];
        } else  if (Crud::PAGE_DETAIL === $pageName) {
            return [$name, $description, $category, $imageFile];
        }
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setEntityLabelInSingular('Product')
        ->setEntityLabelInPlural('Products') 
        ->setPageTitle('index', '%entity_label_plural% Cafeteria')
        ;
       
    }
}
