<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
         $category = new Category();
         $category->setName('Comida');
         $category->setState(1);

         $category = new Category();
         $category->setName('Café');
         $category->setState(1);

         $category = new Category();
         $category->setName('Refescos');
         $category->setState(1);

        $manager->persist($category);
        $manager->flush();
    }
}
