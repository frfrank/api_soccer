<?php

namespace App\Controller\Admin;

use App\Entity\Blocks;
use App\Entity\Company;
use App\Entity\Question;
use App\Entity\TypeQuestion;
use App\Entity\User;
use App\Traits\TranslatorTrait;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class DashboardController extends AbstractDashboardController
{
    use TranslatorTrait;

    protected TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator, AdminUrlGenerator $adminUrlGenerator, CrudUrlGenerator $crudUrlGenerator)
    {
        $this->translator = $translator;
        $this->adminUrlGenerator = $adminUrlGenerator;
        $this->crudUrlGenerator = $crudUrlGenerator;
    }

    /**
     * @Route("/admin", name="admin")     
     */
    public function index(): Response
    {
        // return parent::index();

        if (in_array(User::ROLE_CLUB, $this->getUser()->getRoles()) || in_array(User::ROLE_COACH, $this->getUser()->getRoles())) {
            $url = $this->adminUrlGenerator
                ->setController(UserCrudController::class)
                ->generateUrl();
        }
       
        $url = $this->adminUrlGenerator
            ->setController(UserCrudController::class)
            ->generateUrl();



        return $this->redirect($url);
    }


    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('STARTUP');
    }


    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud($this->trans('blocks.labelInPlural'), 'fas fa-shapes', Blocks::class)->setPermission(User::ROLE_ADMIN);
        yield MenuItem::linkToCrud($this->trans('typequestion.labelInPlural'), 'fas fa-th-large', TypeQuestion::class)->setPermission(User::ROLE_ADMIN);
        yield MenuItem::linkToCrud($this->trans('question.labelInPlural'), 'fas fa-th-large', Question::class)->setPermission(User::ROLE_ADMIN);
        yield MenuItem::linkToCrud($this->trans('company.labelInPlural'), 'fas fa-building', Company::class)->setPermission(User::ROLE_ADMIN);
        yield MenuItem::linkToCrud($this->trans('user.labelInPlural'), 'fas fa-users', User::class);
    }


    public function configureAssets(): Assets
    {
        return Assets::new()
            ->addWebpackEncoreEntry('admin');
    }


    public function configureCrud(): Crud
    {
        return Crud::new()
            // this defines the pagination size for all CRUD controllers
            // (each CRUD controller can override this value if needed)
            //->showEntityActionsAsDropdown()
            ->setPaginatorPageSize(30);
    }
}
