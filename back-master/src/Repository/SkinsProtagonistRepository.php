<?php

namespace App\Repository;

use App\Entity\SkinsProtagonist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SkinsProtagonist|null find($id, $lockMode = null, $lockVersion = null)
 * @method SkinsProtagonist|null findOneBy(array $criteria, array $orderBy = null)
 * @method SkinsProtagonist[]    findAll()
 * @method SkinsProtagonist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SkinsProtagonistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SkinsProtagonist::class);
    }

    // /**
    //  * @return SkinsProtagonist[] Returns an array of SkinsProtagonist objects
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
    public function findOneBySomeField($value): ?SkinsProtagonist
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
