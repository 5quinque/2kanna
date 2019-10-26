<?php

namespace App\Repository;

use App\Entity\Banned;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Banned|null find($id, $lockMode = null, $lockVersion = null)
 * @method Banned|null findOneBy(array $criteria, array $orderBy = null)
 * @method Banned[]    findAll()
 * @method Banned[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BannedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Banned::class);
    }

    public function countEntities()
    {
        return count($this->findAll());
    }

    public function findByUnbanBeforeNow(): ?array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.unbanTime < :olderThan')
            ->setParameter('olderThan', new \DateTime())
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Banned[] Returns an array of Banned objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Banned
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
