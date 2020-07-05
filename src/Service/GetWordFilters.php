<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\WordFilterRepository;

class GetWordFilters
{
    private $em;
    private $wordFilterRepository;

    public function __construct(EntityManagerInterface $em, WordFilterRepository $wordFilterRepository)
    {
        $this->em = $em;
        $this->wordFilterRepository = $wordFilterRepository;
    }

    public function findAllFilters()
    {
        return $this->wordFilterRepository->findAll();
    }
}
