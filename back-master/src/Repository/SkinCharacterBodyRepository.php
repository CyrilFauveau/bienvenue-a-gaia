<?php

namespace App\Repository;

use App\Entity\SkinCharacterBody;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SkinCharacterBody|null find($id, $lockMode = null, $lockVersion = null)
 * @method SkinCharacterBody|null findOneBy(array $criteria, array $orderBy = null)
 * @method SkinCharacterBody[]    findAll()
 * @method SkinCharacterBody[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SkinCharacterBodyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SkinCharacterBody::class);
    }

    // /**
    //  * @return SkinCharacterBody[] Returns an array of SkinCharacterBody objects
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
    public function findOneBySomeField($value): ?SkinCharacterBody
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
