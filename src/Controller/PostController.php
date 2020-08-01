<?php

namespace App\Controller;

use App\Entity\Board;
use App\Entity\Post;
use App\Form\PostType;
use App\Util\PostUtil;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/{board}/{post<\d+>}/{child<\d+>?}",
     * name="post_show",
     * methods={"GET", "POST"})
     * @ParamConverter("board", options={"mapping": {"board": "name"}})
     */
    public function show(Board $board, Post $post, Post $child = null, Request $request, PostUtil $postUtil): Response
    {
        if ($board != $post->getBoard()) {
            throw $this->createNotFoundException('Post Not Found');
        }

        $childPost = new Post();
        $childPost->setBoard($post->getBoard());
        $childPost->setParentPost($post);

        $form = $this->createForm(PostType::class, $childPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postUtil->createPost($childPost);

            return $this->redirectToRoute('post_show', [
                'board' => $board->getName(),
                'post' => $post->getRootParentPost(),
                'child' => $childPost->getId(),
            ]);
        }

        return $this->render('post/show.html.twig', [
            'post' => $post->getRootParentPost(),
            'child' => $child ?? new Post(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Post $post, PostUtil $postUtil): Response
    {
        $boardName = $post->getBoard()->getName();

        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $postUtil->deletePost($post);
        }

        if ($post->getParentPost()) {
            return $this->redirectToRoute('post_show', [
                'name' => $boardName,
                'id' => $post->getRootParentPost()->getId(),
            ]);
        }

        return $this->redirectToRoute('board_show', ['name' => $boardName]);
    }
}
