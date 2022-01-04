<?php

namespace App\Controller\Admin;


use App\Traits\SerializerTrait;
use App\Traits\TranslatorTrait;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;


abstract class CrudController extends AbstractCrudController
{
    use SerializerTrait, TranslatorTrait;

    protected ParameterBagInterface $params;
    protected EntityManagerInterface $em;
    protected AdminContextProvider $context;
    protected ValidatorInterface $validator;
    protected TranslatorInterface $translator;
    protected AdminUrlGenerator $adminUrlGenerator;


    public function __construct (ParameterBagInterface $params, EntityManagerInterface $em,
                                 AdminContextProvider $context, ValidatorInterface $validator,
                                 TranslatorInterface $translator, AdminUrlGenerator $adminUrlGenerator)
    {
        $this->params     = $params;
        $this->em         = $em;
        $this->context    = $context;
        $this->validator  = $validator;
        $this->translator = $translator;
        $this->adminUrlGenerator = $adminUrlGenerator;
    }
}
