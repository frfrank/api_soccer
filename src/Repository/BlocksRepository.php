<?php

namespace App\Repository;

use App\Entity\Blocks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Blocks|null find($id, $lockMode = null, $lockVersion = null)
 * @method Blocks|null findOneBy(array $criteria, array $orderBy = null)
 * @method Blocks[]    findAll()
 * @method Blocks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlocksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Blocks::class);
    }

    // /**
    //  * @return Blocks[] Returns an array of Blocks objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Blocks
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
