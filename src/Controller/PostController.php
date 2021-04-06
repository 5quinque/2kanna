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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", priority=-1)
 */
class PostController extends AbstractController
{

    /**
     * @Route("/json/tree/{post<\w+>}",
     * name="json_post_tree",
     * methods={"GET"})
     * @ParamConverter("board", options={"mapping": {"board": "name"}})
     * @ParamConverter("post", options={"mapping": {"post": "slug"}})
     */
    public function jsonPostTree(Post $post, PostUtil $postUtil)
    {
        $data = $postUtil->getSlugTree($post);

        return new JsonResponse($data);
    }

    /**
     * @Route("/i/{board}/{post<\w+>}",
     * name="individual_post",
     * methods={"GET"})
     * @ParamConverter("board", options={"mapping": {"board": "name"}})
     * @ParamConverter("post", options={"mapping": {"post": "slug"}})
     */
    public function ajaxPost(Board $board, Post $post)
    {
        return $this->render('post/_post.html.twig', ['post' => $post, 'ajax' => true]);
    }

    /**
     * @Route("/{board}/{post<\w+>}/{child<\w+>?}",
     * name="post_show",
     * methods={"GET", "POST"})
     * @ParamConverter("board", options={"mapping": {"board": "name"}})
     * @ParamConverter("post", options={"mapping": {"post": "slug"}})
     * @ParamConverter("child", options={"mapping": {"child": "slug"}})
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
            $postUtil->createPost($childPost, $request);

            return $this->redirectToRoute('post_show', [
                'board' => $board->getName(),
                'post' => $post->getRootParentPost()->getSlug(),
                'child' => $childPost->getSlug(),
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
        if (!$this->isGranted('ROLE_ADMIN')) {
            $board = $post->getBoard();
            $this->denyAccessUnlessGranted('BOARD_EDIT', $board);
        }

        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $postUtil->deletePost($post);

            $this->addFlash(
                'success',
                'Post Deleted'
            );
        }

        return $this->formRedirect($post->getBoard()->getName(), $post);
    }

    /**
     * @Route("/makesticky/{id}", name="post_make_sticky", methods={"POST"}, priority=1)
     */
    public function makeSticky(Request $request, Post $post): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            $board = $post->getBoard();
            dump($board);
            $this->denyAccessUnlessGranted('BOARD_EDIT', $board);
        }

        if ($this->isCsrfTokenValid('make_sticky'.$post->getId(), $request->request->get('_token'))) {
            if ($post->getSticky()) {
                $post->setSticky(false);
                $flashMessage = 'Sticky removed';
            } else {
                $post->setSticky(true);
                $flashMessage = 'Post Stickied';
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash('success', $flashMessage);
        }

        return $this->formRedirect($post->getBoard()->getName(), $post);
    }

    private function formRedirect(string $boardName, Post $post)
    {
        if ($post->getParentPost()) {
            return $this->redirectToRoute('post_show', [
                'board' => $boardName,
                'post' => $post->getRootParentPost()->getSlug(),
            ]);
        }

        return $this->redirectToRoute('board_show', ['name' => $boardName]);
    }
}
