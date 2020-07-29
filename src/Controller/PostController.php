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
     * @Route("/{name}/p/{id}",
     * name="post_show",
     * methods={"GET", "POST"},
     * requirements={"id"="\d+"})
     * @ParamConverter("board", options={"mapping": {"name": "name"}})
     * @ParamConverter("post", options={"mapping": {"id": "id"}})
     */
    public function show(Board $board, Post $post, Request $request, PostUtil $postUtil): Response
    {
        $childPost = new Post();
        $childPost->setBoard($post->getBoard());
        $childPost->setParentPost($post);

        $form = $this->createForm(PostType::class, $childPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postUtil->createPost($childPost);
        }

        return $this->render('post/show.html.twig', [
            'post' => $post->getRootParentPost(),
            'new_post_id' => $childPost->getId(),
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
