<?php

namespace App\Tests\Controller\Admin;

use App\Repository\AdminRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @covers \App\Controller\Admin\AdminController
 */
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

    /**
     * @covers \App\Controller\PostController::delete
     */
    public function testDeletePost()
    {
        $client = static::createClient();

        // Login as admin
        $adminRepository = static::$container->get(AdminRepository::class);
        $testAdmin = $adminRepository->findOneByUsername('admin');
        $client->loginUser($testAdmin, 'default');

        [$post] = self::$container->get(PostRepository::class)->findAll();

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
