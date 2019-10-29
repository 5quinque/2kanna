<?php

namespace App\Service;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CrossLink
{
    private $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    public function generateBoardUrl(string $board)
    {
        return $this->router->generate('board_show', ['name' => $board]);
    }

    public function generatePostUrl(string $board, int $post)
    {
        return $this->router->generate('post_show', ['name' => $board, 'id' => $post]);
    }
}