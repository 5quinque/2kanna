<?php

namespace App\Repository;

use App\Entity\Board;
use App\Entity\Post;
use App\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|Post find($id, $lockMode = null, $lockVersion = null)
 * @method null|Post findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findLatest(int $page = 1, Board $board): Paginator
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.board = :board')
            ->andWhere('p.parent_post is null')
            ->addOrderBy('p.sticky', 'DESC')
            ->addOrderBy('p.latestpost', 'DESC')
            ->setParameter('board', $board)
        ;

        return (new Paginator($qb))->paginate($page);
    }

    public function findByParentOlderThan($value, $ipAddress = null): ?array
    {
        $query = $this->createQueryBuilder('p')
            ->andWhere('p.latestpost < :olderThan')
            ->setParameter('olderThan', new \DateTime($value))
        ;

        if (null !== $ipAddress) {
            $query
                ->andWhere('p.ipAddress = :ipAddress')
                ->setParameter('ipAddress', $ipAddress)
            ;
        }

        return $query->getQuery()
            ->getResult()
        ;
    }

    public function findByChildNewerThan($value, $ipAddress = null): ?array
    {
        $query = $this->createQueryBuilder('p')
            ->andWhere('p.created > :olderThan')
            ->setParameter('olderThan', new \DateTime($value))
        ;

        if (null !== $ipAddress) {
            $query
                ->andWhere('p.ipAddress = :ipAddress')
                ->setParameter('ipAddress', $ipAddress)
            ;
        }

        return $query->getQuery()
            ->getResult()
        ;
    }
}
