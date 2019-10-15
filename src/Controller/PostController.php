<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Board;
use App\Form\PostType;
use App\Repository\PostRepository;
use DateTime;
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
     * @Route("/new", name="post_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $post = new Post();
        $post->setCreated(new DateTime());

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rootPost = $post->getRootParentPost()->setLatestpost(new DateTime);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($rootPost);
            $entityManager->persist($post);
            $entityManager->flush();

            $boardName = $post->getBoard()->getName();
            $newPostId = $post->getId();

            return $this->redirectToRoute('post_show', [
                'name' => $boardName,
                'id' => $rootPost->getId(),
                // Only show newPostId if it's a child post
                'newPostId' => $rootPost->getId() == $newPostId ? null : $newPostId
            ]);
        }
        // [todo] eventually get rid of this
        return $this->render('post/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/{newPostId?}", methods={"GET"}, requirements={"id"="\d+", "newPostId"="\d+"})
     */
    public function show(Post $post, int $newPostId = null): Response
    {
        $newChildPost = new Post();
        $newChildPost->setBoard($post->getBoard());
        $newChildPost->setParentPost($post);

        $form = $this->createForm(PostType::class, $newChildPost, [
            'action' => $this->generateUrl('post_new'),
        ]);

        return $this->render('post/show.html.twig', [
            'post' => $post,
            'new_post_id' => $newPostId,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="post_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Post $post): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('post_show', [
                'name' => $post->getBoard()->getName(),
                'id' => $post->getId()
                ]);
        }

        return $this->render('post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Post $post): Response
    {
        $boardIndex = $post->getBoard()->getName();
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($post);
            $entityManager->flush();
        }

        $boardName = $post->getBoard()->getName();

        if ($post->getParentPost()) {
            return $this->redirectToRoute('post_show', [
                'name' => $boardName,
                'id' => $post->getRootParentPost()->getId()
                ]);
        } else {
            return $this->redirectToRoute('board_show', ['name' => $boardIndex]);
        }
    }
}
