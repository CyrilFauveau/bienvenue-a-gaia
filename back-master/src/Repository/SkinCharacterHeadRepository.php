<?php

namespace App\Repository;

use App\Entity\SkinCharacterHead;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SkinCharacterHead|null find($id, $lockMode = null, $lockVersion = null)
 * @method SkinCharacterHead|null findOneBy(array $criteria, array $orderBy = null)
 * @method SkinCharacterHead[]    findAll()
 * @method SkinCharacterHead[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SkinCharacterHeadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SkinCharacterHead::class);
    }

    // /**
    //  * @return SkinCharacterHead[] Returns an array of SkinCharacterHead objects
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
    public function findOneBySomeField($value): ?SkinCharacterHead
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
