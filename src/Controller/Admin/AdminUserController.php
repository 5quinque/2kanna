<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\User\UserNameType;
use App\Form\User\UserPasswordType;
use App\Form\User\NewUserType;
use App\Repository\UserRepository;
use App\Util\UserUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/users", name="admin_users")
     */
    public function Users(
        UserRepository $adminRepository,
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        UserUtil $userUtil
    ) {
        $user = new User();
        $userForm = $this->createForm(NewUserType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $user->setPassword($passwordEncoder->encodePassword(
                $user,
                $user->getPassword()
            ));

            $userUtil->addUser($user);
            $this->addFlash(
                'success',
                $user->getUsername().' is now created'
            );
        }

        $users = $adminRepository->findAll();

        return $this->render('admin/users/index.html.twig', [
            'users' => $users,
            'user_form' => $userForm->createView(),
        ]);
    }

    /**
     * @Route("/admin/users/edit/{username}", name="admin_users_edit")
     */
    public function UserEdit(
        User $user,
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        UserUtil $userUtil
    ) {
        $userForm = $this->createForm(UserNameType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $userUtil->addUser($user);

            $this->addFlash(
                'success',
                $user->getUsername().' now updated'
            );

            return $this->redirectToRoute('admin_user_edit', ['username' => $user->getUsername()]);
        }

        return $this->render('admin/users/user_edit.html.twig', [
            'user_form' => $userForm->createView(),
        ]);
    }

    /**
     * @Route("/admin/users/remove/{username}", name="admin_users_remove", methods={"DELETE"})
     */
    public function removeUser(Request $request, User $user)
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        $this->addFlash(
            'success',
            $user->getUsername().' is now removed'
        );

        return $this->redirectToRoute('admin_users');
    }

    /**
     * @Route("/admin/users/password/{username}", name="admin_users_password")
     */
    public function UserPassword(
        User $user,
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        UserUtil $userUtil
    ) {
        $userForm = $this->createForm(UserPasswordType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $user->setPassword($passwordEncoder->encodePassword(
                $user,
                $user->getPassword()
            ));
            $userUtil->addUser($user);

            $this->addFlash(
                'success',
                $user->getUsername().' password updated'
            );
        }

        return $this->render('admin/users/user_password.html.twig', [
            'user_form' => $userForm->createView(),
        ]);
    }
}
