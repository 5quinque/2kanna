<?php

namespace App\Controller\Admin;

use App\Entity\Board;
use App\Form\Board\NewBoardType;
use App\Repository\BoardRepository;
use App\Repository\PostRepository;
use App\Util\BoardUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminBoardController extends AbstractController
{
    /**
     * @Route("/admin/boards", name="admin_boards")
     */
    public function boards(
        Request $request,
        BoardRepository $boardRepository,
        PostRepository $post,
        BoardUtil $boardUtil
    ): Response {
        $boards = $boardRepository->findAll();
        $postCount = [];

        foreach ($boards as $b) {
            $bp = $post->findLatest(1, $b);
            $postCount[$b->getName()] = $bp->getNumResults();
        }

        $board = new Board();
        $form = $this->createForm(NewBoardType::class, $board);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($board);
            $entityManager->flush();

            $boardUtil->clearSetting('boardlist');

            $this->addFlash(
                'success',
                'New Board added'
            );

            return $this->redirectToRoute('admin_boards');
        }

        return $this->render('admin/boards/index.html.twig', [
            'boards' => $boards,
            'postCount' => $postCount,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/boards/{name}", name="admin_board_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Board $board): Response
    {
        if ($this->isCsrfTokenValid('delete'.$board->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($board);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Board deleted'
            );
        }

        return $this->redirectToRoute('admin_boards');
    }
}
