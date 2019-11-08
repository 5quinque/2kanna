<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\BannedRepository;
use App\Repository\PostRepository;
use App\Entity\Admin;
use App\Entity\Post;
use App\Entity\Banned;
use App\Entity\WordFilter;
use App\Form\BannedType;
use App\Form\WordFilterType;
use App\Form\AdminUser;
use App\Repository\WordFilterRepository;
use App\Repository\AdminRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_index")
     */
    public function index(
        BannedRepository $bannedRepository,
        WordFilterRepository $wordFilterRepository
    ): Response {
        $bannedCount = $bannedRepository->countEntities();
        $wordFilterCount = $wordFilterRepository->countEntities();

        return $this->render('admin/index.html.twig', [
            'banned_count' => $bannedCount,
            'word_filter_count' => $wordFilterCount,
        ]);
    }

    /**
     * @Route("/admin/ip/{ipAddress}", name="admin_ip_posts")
     */
    public function showPostsByIP(string $ipAddress, PostRepository $postRepository)
    {
        $posts = $postRepository->findBy(['ipAddress' => $ipAddress]);

        return $this->render('admin/ip_post.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/admin/users", name="admin_users")
     */
    public function Users(AdminRepository $adminRepository, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new Admin();
        $userForm = $this->createForm(AdminUser::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $user->setPassword($passwordEncoder->encodePassword(
                $user,
                $user->getPassword()
            ));

            $this->addUser($user);
        }

        $users = $adminRepository->findAll();
        return $this->render('admin/users.html.twig', [
            'users' => $users,
            'user_form' => $userForm->createView()
        ]);
    }

    /**
     * @Route("/admin/users/edit/{id}", name="admin_user_edit")
     */
    public function UserEdit(Admin $user, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $userForm = $this->createForm(AdminUser::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $user->setPassword($passwordEncoder->encodePassword(
                $user,
                $user->getPassword()
            ));

            $this->addUser($user);
        }

        return $this->render('admin/user_edit.html.twig', [
            'user_form' => $userForm->createView()
        ]);

    }

    public function addUser(Admin $user)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash(
            'success',
            $user->getUsername() . " is now created :)"
        );
    }

    /**
     * @Route("/admin/wordfilter", name="admin_wordfilter")
     */
    public function wordFilter(WordFilterRepository $wordFilterRepository, Request $request): Response
    {
        $wordFilter = new WordFilter();
        $wordFilterForm = $this->createForm(WordFilterType::class, $wordFilter);
        $wordFilterForm->handleRequest($request);

        $wordFilterStrings = $wordFilterRepository->findAll();

        if ($wordFilterForm->isSubmitted() && $wordFilterForm->isValid()) {
            $this->addBadWord($wordFilter);
        }

        return $this->render('admin/wordfilter.html.twig', [
            'word_filter_strings' => $wordFilterStrings,
            'word_filter_form' => $wordFilterForm->createView()
        ]);
    }

    /**
     * @Route("/admin/bans", name="admin_banned")
     */
    public function banned(BannedRepository $bannedRepository, Request $request): Response
    {
        $banned = new Banned();
        $bannedForm = $this->createForm(BannedType::class, $banned);
        $bannedForm->handleRequest($request);
        $bannedIPs = $bannedRepository->findAll();

        if ($bannedForm->isSubmitted() && $bannedForm->isValid()) {
            $this->banIP($banned);
        }

        return $this->render('admin/banned.html.twig', [
            'banned_ips' => $bannedIPs,
            'banned_form' => $bannedForm->createView()
        ]);
    }

    /**
     * @Route("/admin/unban/{id}", name="admin_unban", methods={"DELETE"})
     */
    public function unban(Request $request, Banned $banned): Response
    {
        if ($this->isCsrfTokenValid('delete'.$banned->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($banned);
            $entityManager->flush();
        }

        $this->addFlash(
            'success',
            $banned->getIpAddress() . " is now unbanned"
        );

        return $this->redirectToRoute('admin_index');
    }

    /**
     * @Route("/admin/removefilter/{id}", name="admin_removefilter", methods={"DELETE"})
     */
    public function removeFilter(Request $request, WordFilter $wordFilter)
    {
        if ($this->isCsrfTokenValid('delete'.$wordFilter->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($wordFilter);
            $entityManager->flush();
        }

        $this->addFlash(
            'success',
            $wordFilter->getBadWord() . " is now removed"
        );

        return $this->redirectToRoute('admin_index');
    }

    public function banIP(Banned $banned)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($banned);
        $entityManager->flush();

        $this->addFlash(
            'success',
            $banned->getIpAddress() . " is now banned :)"
        );
    }

    public function addBadWord(WordFilter $wordFilter)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($wordFilter);
        $entityManager->flush();

        $this->addFlash(
            'success',
            $wordFilter->getBadWord() . " is added to the bad words list"
        );
    }
}
