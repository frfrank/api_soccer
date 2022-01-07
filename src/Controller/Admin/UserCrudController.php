<?php

namespace App\Controller\Admin;

use App\Entity\Company;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;

/**
 * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_CLUB') or is_granted('ROLE_COACH')")
 */
class UserCrudController extends CrudController
{

    private UserPasswordHasherInterface $userPasswordHasher;
    private $logger;

    public function __construct(
        ParameterBagInterface $params,
        EntityManagerInterface $em,
        AdminContextProvider $context,
        ValidatorInterface $validator,
        TranslatorInterface $translator,
        AdminUrlGenerator $adminUrlGenerator,
        UserPasswordHasherInterface $userPasswordHasher,
        LoggerInterface $logger
    ) {
        parent::__construct($params, $em, $context, $validator, $translator, $adminUrlGenerator);
        $this->userPasswordHasher = $userPasswordHasher;
        $this->logger = $logger;
    }


    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular($this->trans('user.labelInSingular'))
            ->setEntityLabelInPlural($this->trans('user.labelInPlural'))
            ->setSearchFields(['email']);
    }


    public function configureActions(Actions $actions): Actions
    {
        $impersonate = Action::new('impersonate', $this->trans('user.impersonateAction'))
            ->linkToCrudAction('impersonate')
            ->setHtmlAttributes(['target' => '_blank']);

        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_INDEX, $impersonate);
    }

    public function configureFields(string $pageName): iterable
    {
        $user =  $this->getUser()->getCompany()->getId();

        $panelAccess       = FormField::addPanel($this->trans('user.panel.access'));

        $firstName = TextField::new('first_name')
            ->setLabel($this->trans('user.configureFields.firstName'))
            ->setColumns('col-md-6 col-xl-6');

        $lastName = TextField::new('last_name')
            ->setLabel($this->trans('user.configureFields.lastName'))
            ->setColumns('col-md-6 col-xl-6');

        $email = TextField::new('email')
            ->setLabel($this->trans('user.configureFields.email'))
            ->setColumns('col-md-6 col-xl-4');

        $password = TextField::new('plainPassword')
            ->setLabel($this->trans('user.configureFields.password'))
            ->setFormType(PasswordType::class)
            ->setRequired(false)
            ->setFormTypeOption('empty_data', '')
            ->setColumns('col-md-6 col-xl-4');

        $roles = ChoiceField::new('roles')
            ->setChoices(array_flip(User::ROLES))
            ->allowMultipleChoices(true)
            ->setLabel($this->trans('user.configureFields.roles'))
            ->setColumns('col-md-6 col-xl-4');

        $roles_club = ChoiceField::new('roles')
            ->setChoices(array_flip(User::ROLES_CLUB))
            ->allowMultipleChoices(true)
            ->setLabel($this->trans('user.configureFields.roles'))
            ->setColumns('col-md-6 col-xl-4');


        /*  $panelCompany  = FormField::addPanel($this->trans('user.panel.company'))->addCssClass('text-primary'); */

        $companies = AssociationField::new('company')
            ->setLabel($this->trans('user.configureFields.company'))
            ->setColumns('col-md-6 col-xl-4');


        if (in_array($pageName, [Crud::PAGE_INDEX, Crud::PAGE_DETAIL])) {
            return [$email,  $companies->setPermission(User::ROLE_ADMIN), $roles];
        } else if (in_array($pageName, [Crud::PAGE_NEW])) {
            if (Crud::PAGE_NEW === $pageName) {
                $password->setRequired(true);
            }

            return [$panelAccess, $firstName, $lastName, $email, $password, $roles_club->setPermission(User::ROLE_CLUB), $roles->setPermission(User::ROLE_ADMIN), $companies->setPermission(User::ROLE_ADMIN)];
        } else if (in_array($pageName, [Crud::PAGE_EDIT])) {
            if (Crud::PAGE_NEW === $pageName) {
                $password->setRequired(true);
            }

            $field = [$panelAccess, $firstName, $lastName, $email, $password, $roles_club->setPermission(User::ROLE_CLUB), $roles->setPermission(User::ROLE_ADMIN), $companies->setPermission(User::ROLE_ADMIN)];
            return $field;
        }


        return [];
    }


    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance->getPlainPassword()) {
            $entityInstance->setPassword($this->userPasswordHasher->hashPassword($entityInstance, $entityInstance->getPlainPassword()));
        }

        if (empty($entityInstance->getRoles())) {
            $entityInstance->addRole(User::ROLE_USER);
        }

        parent::updateEntity($entityManager, $entityInstance);
    }


    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance->getPlainPassword()) {
            $entityInstance->setPassword($this->userPasswordHasher->hashPassword($entityInstance, $entityInstance->getPlainPassword()));
        }

        if (empty($entityInstance->getRoles())) {            
            $entityInstance->addRole(User::ROLE_USER);
        }
        

        parent::persistEntity($entityManager, $entityInstance);
    }


    public function impersonate(AdminContext $context)
    {
        /**
         * @var User $user
         */
        $user = $this->context->getContext()->getEntity()->getInstance();

        $length          = 40;
        $impersonateCode = substr(bin2hex(random_bytes($length)), 0, $length);

        $user->setImpersonateCode($impersonateCode);
        $this->em->flush();

        return $this->redirect('/user/impersonate/' . $impersonateCode);
    }

    public function createEntity(string $entityFqcn)
    {
        $userSession =  $this->getUser();
        $companies = $this->em->getRepository(Company::class)->find($userSession->getCompany());

        $user = new User();

        if (in_array(User::ROLE_CLUB, $this->getUser()->getRoles())) {
            $user->setCompany($companies);
        }
        else if(in_array(User::ROLE_COACH, $this->getUser()->getRoles())){
            $user->setCompany($companies);
            $user->addRole(User::ROLE_PLAYER);           

        }
        
        
        return $user;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $qb = $this->get(EntityRepository::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);

        if (in_array("ROLE_CLUB", $this->getUser()->getRoles()) && !in_array("ROLE_ADMIN", $this->getUser()->getRoles())) {
            $qb->andWhere('entity.createdBy = :user');
            $qb->setParameter('user', $this->getUser());

            return  $qb;
        }
        else if(in_array("ROLE_COACH", $this->getUser()->getRoles()) && !in_array("ROLE_ADMIN", $this->getUser()->getRoles())){
            $qb->andWhere('entity.createdBy = :user');
            $qb->setParameter('user', $this->getUser());

            return  $qb;
        }

        return  $qb;
    }

}
