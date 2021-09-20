<?php

namespace App\Repository;

use App\Entity\AppUserAddress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AppUserAddress|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppUserAddress|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppUserAddress[]    findAll()
 * @method AppUserAddress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppUserAddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppUserAddress::class);
    }

    // /**
    //  * @return AppUserAddress[] Returns an array of AppUserAddress objects
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
    public function findOneBySomeField($value): ?AppUserAddress
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
