<?php

namespace App\Repository;

use App\Entity\StatisticCompetence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StatisticCompetence|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatisticCompetence|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatisticCompetence[]    findAll()
 * @method StatisticCompetence[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatisticCompetenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StatisticCompetence::class);
    }

    // /**
    //  * @return StatisticCompetence[] Returns an array of StatisticCompetence objects
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
    public function findOneBySomeField($value): ?StatisticCompetence
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    //  public function findmewhy()
    // {
    //     $entityManager = $this->getEntityManager();
    //     $query = $entityManager->getConnection()->prepare("SELECT * FROM `apprenant`,`user`, `statistic_competence` WHERE `apprenant`.`id`=25 AND `user`.id=25 AND `statistic_competence`.`apprenant_id`= `apprenant`.`id`");
    //     $query->execute(array("idAp" => 25));
    //     $result = $query->fetchAll();

    //     return $result; 
    // }
}
