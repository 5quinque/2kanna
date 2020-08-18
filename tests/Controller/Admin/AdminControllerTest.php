<?php

namespace App\Tests\Controller\Admin;

use App\Repository\AdminRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AdminControllerTest extends WebTestCase
{
    public function testAdminIndex()
    {
        $client = static::createClient();
        $adminRepository = static::$container->get(AdminRepository::class);

        // retrieve the admin user
        $testAdmin = $adminRepository->findOneByUsername('admin');

        // simulate $testAdmin being logged in
        $client->loginUser($testAdmin, 'default');

        // test e.g. the profile page
        $client->request('GET', '/admin');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1#admin', 'Admin Dashboard');
    }

    public function testAdminLoginView()
    {
        $client = static::createClient();

        $client->request('GET', '/admin/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('button[type="submit"]', 'Login');
    }

    public function testShowPostsByIP()
    {
        $client = static::createClient();
        $adminRepository = static::$container->get(AdminRepository::class);
        $postRepository = static::$container->get(PostRepository::class);

        $posts = $postRepository->findBy(['ipAddress' => '127.0.0.1']);

        $testAdmin = $adminRepository->findOneByUsername('admin');
        $client->loginUser($testAdmin, 'default');

        $crawler = $client->request('GET', '/admin/ip/127.0.0.1');

        $this->assertResponseIsSuccessful();

        foreach ($posts as $p) {
            if (!is_null($p->getMessage())) {
                $this->assertSelectorTextContains(".message:contains('{$p->getMessage()}')", $p->getMessage());
            }
        }
    }

    public function testAdminLogout()
    {
        $client = static::createClient();
        $adminRepository = static::$container->get(AdminRepository::class);

        // retrieve the admin user
        $testAdmin = $adminRepository->findOneByUsername('admin');

        // simulate $testAdmin being logged in
        $client->loginUser($testAdmin, 'default');

        $client->request('GET', '/admin/logout');

        $this->assertResponseRedirects('http://localhost/', Response::HTTP_FOUND);
    }

    public function testDeletePost()
    {
        $client = static::createClient();

        // Login as admin
        $adminRepository = static::$container->get(AdminRepository::class);
        $testAdmin = $adminRepository->findOneByUsername('admin');
        $client->loginUser($testAdmin, 'default');

        // We are only testing against a parent post
        $post = self::$container->get(PostRepository::class)->findOneBy(['parent_post' => null]);

        $postId = $post->getId();

        $url = $client->getContainer()->get('router')->generate(
            'post_show',
            ['board' => $post->getBoard()->getName(), 'post' => $post->getSlug()]
        );
        $deleteUrl = $client->getContainer()->get('router')->generate(
            'post_delete',
            ['id' => $post->getId()]
        );
        $boardUrl = $client->getContainer()->get('router')->generate(
            'board_show',
            ['name' => $post->getBoard()->getName()]
        );

        $crawler = $client->request('GET', $url);
        $client->submit($crawler->filter("form[action='{$deleteUrl}']")->form());

        // Check we redirect
        $this->assertResponseRedirects($boardUrl, Response::HTTP_FOUND);

        $crawler = $client->followRedirect();

        // Check our alert is shown
        $this->assertSelectorTextContains('.alert-success', 'Post Deleted');

        // Check the entity doesn't exist
        $post = self::$container->get(PostRepository::class)->find($postId);
        $this->assertNull($post);
    }
}
