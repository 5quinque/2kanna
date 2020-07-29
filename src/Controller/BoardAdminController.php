<?php

namespace App\Controller;

use App\Entity\Board;
use App\Form\Board\BoardNameType;
use App\Form\Board\BoardPasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/boardadmin")
 */
class BoardAdminController extends AbstractController
{
    /**
     * @Route("/{name}", name="board_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Board $board, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $this->denyAccessUnlessGranted('BOARD_EDIT', $board);

        $form = $this->createForm(BoardNameType::class, $board);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'success',
                $board->getName().' updated'
            );

            return $this->redirectToRoute('board_edit', ['name' => $board->getName()]);
        }

        return $this->render('board_admin/index.html.twig', [
            'board' => $board,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{name}/password", name="board_editpassword", methods={"GET","POST"})
     */
    public function editPassword(Request $request, Board $board, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $this->denyAccessUnlessGranted('BOARD_EDIT', $board);

        $form = $this->createForm(BoardPasswordType::class, $board);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $board->setPassword($passwordEncoder->encodePassword(
                $board,
                $board->getPassword()
            ));

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'success',
                $board->getName().' password updated'
            );
        }

        return $this->render('board_admin/editpassword.html.twig', [
            'board' => $board,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{name}/denied", name="board_denied", methods={"GET"})
     */
    public function denied(Board $board): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();

        if ($board === $user) {
            return $this->redirectToRoute('board_edit', ['name' => $board->getName()]);
        }

        return $this->render('board_admin/denied.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{name}", name="board_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Board $board): Response
    {
        $this->denyAccessUnlessGranted('BOARD_EDIT', $board);

        if ($this->isCsrfTokenValid('delete'.$board->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($board);
            $entityManager->flush();
        }

        return $this->redirectToRoute('board_index');
    }
}
