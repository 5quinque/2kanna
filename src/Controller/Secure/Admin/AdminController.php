<?php

namespace App\Controller\Secure\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\BannedRepository;
use App\Repository\BoardRepository;
use App\Repository\PostRepository;
use App\Repository\WordFilterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_index")
     */
    public function index(
        BannedRepository $bannedRepository,
        BoardRepository $boardRepository,
        WordFilterRepository $wordFilterRepository,
        UserRepository $userRepository
    ): Response {
        $bannedCount = $bannedRepository->countEntities();
        $boardCount = $boardRepository->countEntities();
        $wordFilterCount = $wordFilterRepository->countEntities();
        $userCount = $userRepository->countEntities();

        return $this->render('secure/admin/index.html.twig', [
            'banned_count' => $bannedCount,
            'board_count' => $boardCount,
            'word_filter_count' => $wordFilterCount,
            'user_count' => $userCount,
        ]);
    }

    /**
     * @Route("/admin/ip/{ipAddress}", name="admin_ip_posts")
     */
    public function showPostsByIP(string $ipAddress, PostRepository $postRepository)
    {
        $posts = $postRepository->findBy(['ipAddress' => $ipAddress]);

        return $this->render('secure/admin/ip_post.html.twig', [
            'posts' => $posts,
        ]);
    }
}
