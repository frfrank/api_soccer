<?php

namespace App\Controller\Admin;

use App\Entity\Question;
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
use Symfony\Contracts\Translation\TranslatorInterface;

class QuestionCrudController extends CrudController
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
        return Question::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular($this->trans('question.labelInSingular'))
            ->setEntityLabelInPlural($this->trans('question.labelInPlural'))
            ->setSearchFields(['name']);
    }

    public function configureFields(string $pageName): iterable
    {
        $panelQuestion  = FormField::addPanel($this->trans('question.panel.type'));        

        $name = TextField::new('name')
            ->setLabel($this->trans('Name'))
            ->setColumns('col-md-6 col-xl-6');

        $blocks = AssociationField::new('block')->setLabel($this->trans('blocks.labelInSingular'));

        $type = AssociationField::new('type')->setLabel($this->trans('typequestion.labelInSingular'));


        $state = BooleanField::new('state')->setLabel($this->trans('state')); 



        $createdAt   = DateTimeField::new('createdAt');
        $updatedAt   = DateTimeField::new('updatedAt');
        $createdBy   = AssociationField::new('createdBy');
        $updatedBy   = AssociationField::new('updatedBy');

        $field = [];

        if (Crud::PAGE_INDEX === $pageName) {
            $field = [$name,  $blocks, $type, $state, $createdAt];
        } else  if (in_array($pageName, [Crud::PAGE_NEW, Crud::PAGE_EDIT])) {
            $field = [$panelQuestion,$name, $blocks, $type];
        } else if (Crud::PAGE_DETAIL === $pageName) {
            $field = [$name, $createdAt, $updatedAt, $createdBy, $updatedBy];
        }

        return $field;
    }

    
}
