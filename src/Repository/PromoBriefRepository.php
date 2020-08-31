<?php

namespace App\Repository;

use App\Entity\PromoBrief;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PromoBrief|null find($id, $lockMode = null, $lockVersion = null)
 * @method PromoBrief|null findOneBy(array $criteria, array $orderBy = null)
 * @method PromoBrief[]    findAll()
 * @method PromoBrief[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromoBriefRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PromoBrief::class);
    }

    public function getAllBriefs($id){
        return $this->createQueryBuilder('p')
            ->andWhere('p.promo = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getOneBrief($id,$num){
        return $this->createQueryBuilder('p')
            ->andWhere('p.promo = :val')
            ->andWhere('p.brief = :valu')
            ->setParameter('val', $id)
            ->setParameter('valu', $num)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return PromoBrief[] Returns an array of PromoBrief objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PromoBrief
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
