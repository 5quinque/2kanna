<?php

namespace App\Controller;

use App\Entity\Board;
use App\Entity\Post;
use App\Form\Board\NewBoardType;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Util\BoardUtil;
use App\Util\PostUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/")
 */
class BoardController extends AbstractController
{
    /**
     * @Route("/", name="board_index", methods={"GET"})
     */
    public function index(BoardUtil $boardUtil): Response
    {
        return $this->render('board/index.html.twig', [
            'boards' => $boardUtil->boards(),
            'postCount' => $boardUtil->boardPostCountAll(),
        ]);
    }

    /**
     * @Route("/new", name="board_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder, BoardUtil $boardUtil): Response
    {
        $this->denyAccessUnlessGranted('CAN_CREATE_BOARD');

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

            $boardUtil->clearSetting('boardlist');

            return $this->redirectToRoute('board_show', ['name' => $board->getName()]);
        }

        return $this->render('board/new.html.twig', [
            'board' => $board,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{name}/{page<page>?page}/{page_no<\d+>?1}", name="board_show", methods={"GET", "POST"})
     */
    public function show(Board $board, int $page_no, Request $request, PostRepository $postRepository, PostUtil $postUtil): Response
    {
        $post = new Post();
        $post->setBoard($board);

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postUtil->createPost($post, $request);

            return $this->redirectToRoute('post_show', ['board' => $board->getName(), 'post' => $post->getSlug()]);
        }

        return $this->render('board/show.html.twig', [
            'board' => $board,
            'paginator' => $postRepository->findLatest($page_no, $board),
            'form' => $form->createView(),
        ]);
    }
}
