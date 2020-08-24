<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\User\RegisterType;
use App\Security\LoginFormAuthenticator;
use App\Util\SettingUtil;
use App\Util\UserUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="user_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $user = new User();
        $registerForm = $this->createForm(RegisterType::class, $user, [
            'action' => $this->generateUrl('user_register'),
            'method' => 'POST',
        ]);

        return $this->render(
            'security/login.html.twig',
            [
                'last_username' => $lastUsername,
                'error' => $error,
                'register_form' => $registerForm->createView()
                ]
        );
    }

    /**
     * @Route("/logout", name="user_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }

    /**
     * @Route("/register", name="user_register")
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        GuardAuthenticatorHandler $guardHandler,
        LoginFormAuthenticator $authenticator,
        UserUtil $userUtil,
        SettingUtil $settingUtil
    ) {
        if (!$settingUtil->setting('anon_can_create_board')) {
            return $this->redirectToRoute('board_index');
        }

        $user = new User();

        $registerForm = $this->createForm(RegisterType::class, $user, [
            'action' => $this->generateUrl('user_register'),
            'method' => 'POST',
        ]);

        $registerForm->handleRequest($request);

        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $registerForm->get('plainPassword')->getData()
                )
            );

            $userUtil->addUser($user);
            $this->addFlash(
                'success',
                $user->getUsername().' is now created'
            );

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'default' // firewall name in security.yaml
            );
        }

        return $this->redirectToRoute('user_index');
    }
}
