<?php

namespace App\Repository;

use App\Entity\SettingChoice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SettingChoice|null find($id, $lockMode = null, $lockVersion = null)
 * @method SettingChoice|null findOneBy(array $criteria, array $orderBy = null)
 * @method SettingChoice[]    findAll()
 * @method SettingChoice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SettingChoiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SettingChoice::class);
    }

    // /**
    //  * @return SettingChoice[] Returns an array of SettingChoice objects
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
    public function findOneBySomeField($value): ?SettingChoice
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
