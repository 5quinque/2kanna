<?php

namespace App\Controller;

use App\Entity\Board;
use App\Entity\Post;
use App\Form\BoardType;
use App\Form\PostType;
use App\Repository\BoardRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function new(Request $request): Response
    {
        $board = new Board();
        $form = $this->createForm(BoardType::class, $board);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
     * @Route("/{name}/{page_no<\d+>?0}", name="board_show", methods={"GET"}, requirements={"name"="^((?!post).)*$"})
     */
    public function show(Board $board, int $page_no, PostRepository $postRepository): Response
    {
        $post = new Post();
        $post->setBoard($board);
        $form = $this->createForm(PostType::class, $post, [
            'action' => $this->generateUrl('post_new'),
        ]);

        $repPosts = $postRepository->findBy(['parent_post' => null, 'board' => $board], ["created" => "ASC"], 30, $page_no * 30);
 
        return $this->render('board/show.html.twig', [
            'board' => $board,
            'posts' => $repPosts,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("{name}/post/{id}/{newPostId?}", name="post_show", methods={"GET"}, requirements={"id"="\d+", "newPostId"="\d+"})
     */
    public function showPost(Post $post, int $newPostId = null)
    {
    $response = $this->forward('App\Controller\PostController::show', [
        'post'  => $post,
        'newPostId' => $newPostId
    ]);

    return $response;
}

    /**
     * @Route("/{name}/edit", name="board_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Board $board): Response
    {
        $form = $this->createForm(BoardType::class, $board);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('board_index');
        }

        return $this->render('board/edit.html.twig', [
            'board' => $board,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="board_delete", methods={"DELETE"})
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
