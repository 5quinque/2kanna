<?php

namespace App\Twig;

use App\Repository\BoardRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BoardsExtension extends AbstractExtension
{
    private $repository;

    public function __construct(BoardRepository $boardRepository)
    {
        $this->repository = $boardRepository;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('boards', [$this, 'boards']),
        ];
    }

    public function boards()
    {
        return $this->repository->findAllArr();
    }
}
