<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\PriceProduct;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;


class CoffeeController extends AbstractDashboardController
{

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
  {
    $this->adminUrlGenerator = $adminUrlGenerator;
  }
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
     //   return parent::index();

     $url = $this->adminUrlGenerator
        ->setController(ProductCrudController::class)        
        ->generateUrl();

        return $this->redirect($url);


   /*   $routeBuilder = $this->get(CrudUrlGenerator::class)->build();
     return $this->redirect($routeBuilder->setController(ProductCrudController::class)->generateUrl()); */
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Coffee Bar');
    }

    public function configureMenuItems(): iterable
    {
      //  yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');       
         yield MenuItem::linkToCrud('Category', 'fas fa-list', Category::class);
         yield MenuItem::linkToCrud('Product', 'fas fa-home', Product::class);
         yield MenuItem::linkToCrud('PriceProduct', 'fas fa-home', PriceProduct::class);
    }

     
}
