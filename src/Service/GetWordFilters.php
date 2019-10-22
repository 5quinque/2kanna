<?php

namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;
use App\Repository\WordFilterRepository;

class GetWordFilters
{
    private $om;
    private $wordFilterRepository;

    public function __construct(ObjectManager $objectManager, WordFilterRepository $wordFilterRepository)
    {
        $this->om = $objectManager;
        $this->wordFilterRepository = $wordFilterRepository;
    }

    public function findAllFilters()
    {
        return $this->wordFilterRepository->findAll();
    }
}