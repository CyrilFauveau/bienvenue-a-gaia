<?php

namespace App\Repository;

use App\Entity\SkinDevice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SkinDevice|null find($id, $lockMode = null, $lockVersion = null)
 * @method SkinDevice|null findOneBy(array $criteria, array $orderBy = null)
 * @method SkinDevice[]    findAll()
 * @method SkinDevice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SkinDeviceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SkinDevice::class);
    }

    // /**
    //  * @return SkinDevice[] Returns an array of SkinDevice objects
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
    public function findOneBySomeField($value): ?SkinDevice
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
