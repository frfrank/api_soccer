<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);
        $user->setEmail('admin@admin.com');
        $user->setFirstName('Francisco');
        $user->setLastName('Picado Meza');
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            '1234'
        ));

        $manager->persist($user);
        $manager->flush();
    }
}
