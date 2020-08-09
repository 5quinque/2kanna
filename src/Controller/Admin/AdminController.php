<?php

namespace App\Controller\Admin;

use App\Entity\Admin;
use App\Repository\AdminRepository;
use App\Repository\BannedRepository;
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
        WordFilterRepository $wordFilterRepository,
        AdminRepository $adminRepository
    ): Response {
        $bannedCount = $bannedRepository->countEntities();
        $wordFilterCount = $wordFilterRepository->countEntities();
        $userCount = $adminRepository->countEntities();

        return $this->render('admin/index.html.twig', [
            'banned_count' => $bannedCount,
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

        return $this->render('admin/ip_post.html.twig', [
            'posts' => $posts,
        ]);
    }
}
