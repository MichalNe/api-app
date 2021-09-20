<?php

namespace App\Repository;

use App\Entity\AppHeader;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AppHeader|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppHeader|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppHeader[]    findAll()
 * @method AppHeader[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppHeaderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppHeader::class);
    }
}
