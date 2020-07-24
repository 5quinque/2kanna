<?php

namespace App\Controller\Admin;

use App\Entity\Admin;
use App\Form\Admin\AdminNameType;
use App\Form\Admin\AdminPasswordType;
use App\Form\Admin\NewAdminType;
use App\Repository\AdminRepository;
use App\Util\AdminUtil;
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
        AdminRepository $adminRepository,
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        AdminUtil $adminUtil
    ) {
        $user = new Admin();
        $userForm = $this->createForm(NewAdminType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $user->setPassword($passwordEncoder->encodePassword(
                $user,
                $user->getPassword()
            ));

            $adminUtil->addUser($user);
            $this->addFlash(
                'success',
                $user->getUsername().' is now created :)'
            );
        }

        $users = $adminRepository->findAll();

        return $this->render('admin/users/users.html.twig', [
            'users' => $users,
            'user_form' => $userForm->createView(),
        ]);
    }

    /**
     * @Route("/admin/users/edit/{username}", name="admin_user_edit")
     */
    public function UserEdit(
        Admin $user,
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        AdminUtil $adminUtil
    ) {
        $userForm = $this->createForm(AdminNameType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $user->setPassword($passwordEncoder->encodePassword(
                $user,
                $user->getPassword()
            ));

            $adminUtil->addUser($user);

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
     * @Route("/admin/users/remove/{username}", name="admin_user_remove", methods={"DELETE"})
     */
    public function removeUser(Request $request, Admin $user)
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
     * @Route("/admin/users/password/{username}", name="admin_user_password")
     */
    public function UserPassword(
        Admin $user,
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        AdminUtil $adminUtil
    ) {
        $userForm = $this->createForm(AdminPasswordType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $user->setPassword($passwordEncoder->encodePassword(
                $user,
                $user->getPassword()
            ));

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
