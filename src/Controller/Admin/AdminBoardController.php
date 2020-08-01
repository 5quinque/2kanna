<?php

namespace App\Controller\Admin;

use App\Repository\BoardRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminBoardController extends AbstractController
{
    /**
     * @Route("/admin/boards", name="admin_boards", priority=2)
     */
    public function banned(BoardRepository $board, PostRepository $post): Response
    {
        $boards = $board->findAll();
        $postCount = [];

        foreach ($boards as $b) {
            $bp = $post->findLatest(1, $b);
            $postCount[$b->getName()] = $bp->getNumResults();
        }

        return $this->render('admin/boards.html.twig', [
            'boards' => $boards,
            'postCount' => $postCount,
        ]);
    }
}
