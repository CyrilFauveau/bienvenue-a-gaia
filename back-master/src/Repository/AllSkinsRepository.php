<?php

namespace App\Repository;

use App\Entity\AllSkins;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AllSkins|null find($id, $lockMode = null, $lockVersion = null)
 * @method AllSkins|null findOneBy(array $criteria, array $orderBy = null)
 * @method AllSkins[]    findAll()
 * @method AllSkins[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AllSkinsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AllSkins::class);
    }

    // /**
    //  * @return AllSkins[] Returns an array of AllSkins objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AllSkins
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
