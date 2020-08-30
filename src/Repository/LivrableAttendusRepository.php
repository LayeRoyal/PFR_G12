<?php

namespace App\Repository;

use App\Entity\LivrableAttendus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LivrableAttendus|null find($id, $lockMode = null, $lockVersion = null)
 * @method LivrableAttendus|null findOneBy(array $criteria, array $orderBy = null)
 * @method LivrableAttendus[]    findAll()
 * @method LivrableAttendus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivrableAttendusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LivrableAttendus::class);
    }

    // /**
    //  * @return LivrableAttendus[] Returns an array of LivrableAttendus objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LivrableAttendus
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
