<?php

namespace App\Controller\Secure;

use App\Entity\Board;
use App\Form\Board\NewBoardType;
use App\Repository\BoardRepository;
use App\Repository\PostRepository;
use App\Util\BoardUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/boards")
 */
class ManageBoardsController extends AbstractController
{
    /**
     * @Route("/", name="list_boards")
     */
    public function boards(Request $request, BoardRepository $boardRepository, PostRepository $post, BoardUtil $boardUtil): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $boards = $boardRepository->findAll();
        } else {
            $boards = $this->getUser()->getBoards();
        }

        $board = new Board();
        $form = $this->createForm(NewBoardType::class, $board);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $board->setOwner($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($board);
            $entityManager->flush();

            $boardUtil->clearSetting('boardlist');

            $this->addFlash(
                'success',
                'New Board added'
            );

            return $this->redirectToRoute('list_boards');
        }

        return $this->render('secure/manage_boards/index.html.twig', [
            'boards' => $boards,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{name}", name="board_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Board $board): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            $this->denyAccessUnlessGranted('BOARD_EDIT', $board);
        }

        if ($this->isCsrfTokenValid('delete'.$board->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($board);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Board deleted'
            );
        }

        return $this->redirectToRoute('list_boards');
    }
}