<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Util\PostUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/post")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/{id}/{childId?}", methods={"GET", "POST"}, requirements={"id"="\d+", "childId"="\d+"})
     */
    public function show(Post $post, int $childId = null, Request $request, PostUtil $postUtil): Response
    {
        $childPost = new Post();
        $childPost->setBoard($post->getBoard());
        $childPost->setParentPost($post);

        $form = $this->createForm(PostType::class, $childPost);

        $form->handleRequest($request);

        if ($form->isSubmitted() &&
            $form->isValid()) {
            $postUtil->createPost($childPost);

            $rootPost = $childPost->getRootParentPost();

            return $this->redirectToRoute('post_show', [
                'name' => $childPost->getBoard()->getName(),
                'id' => $rootPost->getId(),
                'childId' => $rootPost->getId() == $childPost->getId() ? null : $childPost->getId(),
            ]);
        }

        return $this->render('post/show.html.twig', [
            'post' => $post->getRootParentPost(),
            'new_post_id' => $childId,
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
