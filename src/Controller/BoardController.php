<?php

namespace App\Controller;

use App\Entity\Board;
use App\Entity\Post;
use App\Form\Board\NewBoardType;
use App\Form\PostType;
use App\Repository\BoardRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class BoardController extends AbstractController
{
    /**
     * @Route("/", name="board_index", methods={"GET"})
     */
    public function index(BoardRepository $boardRepository): Response
    {
        return $this->render('board/index.html.twig', [
            'boards' => $boardRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="board_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $board = new Board();
        $form = $this->createForm(NewBoardType::class, $board);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $board->setPassword($passwordEncoder->encodePassword(
                $board,
                $board->getPassword()
            ));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($board);
            $entityManager->flush();

            return $this->redirectToRoute('board_index');
        }

        return $this->render('board/new.html.twig', [
            'board' => $board,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{name}/{page_no<\d+>?1}", name="board_show", methods={"GET", "POST"})
     */
    public function show(
        Board $board,
        int $page_no,
        Request $request,
        PostRepository $postRepository
    ): Response {
        $post = new Post();
        $post->setBoard($board);
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() &&
            $form->isValid()) {
            return $this->forward('App\Controller\PostController::show', [
                'post' => $post,
            ]);
        }

        $criteria = ['parent_post' => null, 'board' => $board];

        $repPosts = $postRepository->findBy(
            $criteria,                // Criteria
            ['latestpost' => 'DESC'], // Order by
            12,                       // Limit
            ($page_no - 1) * 12       // Offset
        );

        $pageCount = ceil($postRepository->getPageCount($criteria) / 12);

        return $this->render('board/show.html.twig', [
            'board' => $board,
            'posts' => $repPosts,
            'page_count' => $pageCount,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{name}/post/{id}/{childId?}",
     * name="post_show", methods={"GET", "POST"},
     * requirements={"id"="\d+",
     * "childId"="\d+"})
     */
    public function showPost(Post $post, int $childId = null)
    {
        return $this->forward('App\Controller\PostController::show', [
            'post' => $post,
            'childId' => $childId,
        ]);
    }

    /**
     * @Route("/{name}", name="board_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Board $board): Response
    {
        if ($this->isCsrfTokenValid('delete'.$board->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($board);
            $entityManager->flush();
        }

        return $this->redirectToRoute('board_index');
    }
}
