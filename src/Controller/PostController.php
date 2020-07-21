<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Util\HelperUtil;
use App\Util\ImageCache;
use DateTime;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
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
     * @Route("/{id}/{newPostId?}", methods={"GET", "POST"}, requirements={"id"="\d+", "newPostId"="\d+"})
     */
    public function show(Post $post, int $newPostId = null, Request $request, ImageCache $imageCache): Response
    {
        $childPost = new Post();
        $childPost->setBoard($post->getBoard());
        $childPost->setParentPost($post);

        $form = $this->createForm(PostType::class, $childPost);

        $form->handleRequest($request);

        if ($form->isSubmitted() &&
            $form->isValid()) {
            return $this->postFormSubmitted($childPost, $imageCache);
        }

        return $this->render('post/show.html.twig', [
            'post' => $post->getRootParentPost(),
            'new_post_id' => $newPostId,
            'form' => $form->createView(),
        ]);
    }

    public function postFormSubmitted(Post $post, ImageCache $imageCache)
    {
        $post->setCreated(new DateTime());

        $post->setIpAddress(HelperUtil::getIPAddress());

        // We will lose access to Post::imageFile so need to save the mimetype
        if ($post->getImageFile()) {
            $post->setImageMimeType($post->getImageFile()->getMimeType());
        }

        $rootPost = $post->getRootParentPost();
        $boardName = $post->getBoard()->getName();

        // Update parent post timestamp
        $rootPost->setLatestpost(new DateTime());

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($rootPost);
        $entityManager->persist($post);
        $entityManager->flush();

        $imageCache->queueImageFilter($post);

        return $this->redirectToRoute('post_show', [
            'name' => $boardName,
            'id' => $rootPost->getId(),
            // Only show newPostId if it's a child post
            'newPostId' => $rootPost->getId() == $post->getId() ? null : $post->getId(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Post $post, CacheManager $liipCacheManager): Response
    {
        $boardIndex = $post->getBoard()->getName();
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            // Remove LiipImagine image cache
            $liipCacheManager->remove($post->getImageName());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($post);
            $entityManager->flush();
        }

        $boardName = $post->getBoard()->getName();

        if ($post->getParentPost()) {
            return $this->redirectToRoute('post_show', [
                'name' => $boardName,
                'id' => $post->getRootParentPost()->getId(),
            ]);
        }

        return $this->redirectToRoute('board_show', ['name' => $boardIndex]);
    }
}
