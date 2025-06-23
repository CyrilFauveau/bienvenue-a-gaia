<?php

namespace App\Repository;

use App\Entity\SkinProtagonistFace;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SkinProtagonistFace|null find($id, $lockMode = null, $lockVersion = null)
 * @method SkinProtagonistFace|null findOneBy(array $criteria, array $orderBy = null)
 * @method SkinProtagonistFace[]    findAll()
 * @method SkinProtagonistFace[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SkinProtagonistFaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SkinProtagonistFace::class);
    }

    // /**
    //  * @return SkinProtagonistFace[] Returns an array of SkinProtagonistFace objects
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
    public function findOneBySomeField($value): ?SkinProtagonistFace
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
