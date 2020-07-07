<?php

namespace App\Controller;

use App\Entity\Board;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class BoardSecurityController extends AbstractController
{
    /**
     * @Route("/boardadmin/{name}/login", name="board_login")
     */
    public function login(Board $board, AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render(
            'security/boardlogin.html.twig',
            [
            'board' => $board,
            'error' => $error
            ]
        );
    }

    /**
     * @Route("/boardadmin/logout", name="board_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
