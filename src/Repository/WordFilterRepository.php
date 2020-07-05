<?php

namespace App\Repository;

use App\Entity\WordFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WordFilter|null find($id, $lockMode = null, $lockVersion = null)
 * @method WordFilter|null findOneBy(array $criteria, array $orderBy = null)
 * @method WordFilter[]    findAll()
 * @method WordFilter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WordFilterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WordFilter::class);
    }

    public function countEntities()
    {
        return count($this->findAll());
    }
    // /**
    //  * @return WordFilter[] Returns an array of WordFilter objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WordFilter
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
