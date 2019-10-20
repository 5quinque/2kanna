<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function getPageCount(array $criteria)
    {
        return count($this->findBy($criteria));
    }

    public function findByOlderThan($value): ?array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.latestpost < :olderThan')
            ->setParameter('olderThan', new \DateTime($value))
            ->getQuery()
            ->getResult();
    }
}
