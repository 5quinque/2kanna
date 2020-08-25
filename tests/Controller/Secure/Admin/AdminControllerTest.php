<?php

namespace App\Tests\Controller\Secure\Admin;

use App\Repository\UserRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AdminControllerTest extends WebTestCase
{
    public function testAdminIndex()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);

        // retrieve the admin user
        $testAdmin = $userRepository->findOneByUsername('admin');

        // simulate $testAdmin being logged in
        $client->loginUser($testAdmin, 'default');

        // test e.g. the profile page
        $client->request('GET', '/admin');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1#admin', 'Admin Dashboard');
    }

    public function testShowPostsByIP()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $postRepository = static::$container->get(PostRepository::class);

        $posts = $postRepository->findBy(['ipAddress' => '127.0.0.1']);

        $testAdmin = $userRepository->findOneByUsername('admin');
        $client->loginUser($testAdmin, 'default');

        $crawler = $client->request('GET', '/admin/ip/127.0.0.1');

        $this->assertResponseIsSuccessful();

        foreach ($posts as $p) {
            if (!is_null($p->getMessage())) {
                $this->assertSelectorExists("#{$p->getSlug()} .message");
            }
        }
    }

    public function testAdminLogout()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);

        // retrieve the admin user
        $testAdmin = $userRepository->findOneByUsername('admin');

        // simulate $testAdmin being logged in
        $client->loginUser($testAdmin, 'default');

        $client->request('GET', '/logout');

        $this->assertResponseRedirects('http://localhost/', Response::HTTP_FOUND);
    }
}
