<?php

namespace App\Repository;

use App\Entity\SkinBackground;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SkinBackground|null find($id, $lockMode = null, $lockVersion = null)
 * @method SkinBackground|null findOneBy(array $criteria, array $orderBy = null)
 * @method SkinBackground[]    findAll()
 * @method SkinBackground[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SkinBackgroundRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SkinBackground::class);
    }

    // /**
    //  * @return SkinBackground[] Returns an array of SkinBackground objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SkinBackground
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
