<?php

namespace App\Tests\Controller;

use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PostControllerTest extends WebTestCase
{
    public function testPostTree()
    {
        $client = static::createClient();

        $postRepository = self::$container->get(PostRepository::class);
        $post = $postRepository->findOneBy(['parent_post' => null]);

        $client->request('GET', "/json/tree/{$post->getSlug()}");

        $this->assertResponseIsSuccessful();
    }

    public function testAjaxPost()
    {
        $client = static::createClient();

        $postRepository = self::$container->get(PostRepository::class);
        $post = $postRepository->findOneBy(['parent_post' => null]);

        $client->request('GET', "/i/{$post->getBoard()->getName()}/{$post->getSlug()}");

        $this->assertResponseIsSuccessful();
    }

    public function testShowPosts()
    {
        $client = static::createClient();

        /** @var \App\Entity\Post $Posts */
        $posts = self::$container->get(PostRepository::class)->findAll();

        foreach ($posts as $post) {
            $url = $client->getContainer()->get('router')->generate(
                'post_show',
                ['board' => $post->getBoard()->getName(), 'post' => $post->getSlug()]
            );

            $client->request('GET', $url);

            $this->assertResponseIsSuccessful();
        }
    }

    public function testNewChildPost()
    {
        $client = static::createClient();
        $client->followRedirects();

        [$post] = self::$container->get(PostRepository::class)->findAll();

        $url = $client->getContainer()->get('router')->generate(
            'post_show',
            ['board' => $post->getBoard()->getName(), 'post' => $post->getSlug()]
        );

        $client->request('GET', $url);

        $crawler = $client->submitForm(
            'Reply',
            [
                'post[message]' => 'Test Child Message',
            ]
        );

        $newPost = $crawler->filter('.post-highlight .post-body .message')->text();

        $this->assertSame('Test Child Message', $newPost);
    }

    public function testMakeSticky()
    {
        $client = static::createClient();
        $client->followRedirects();

        $userRepository = static::$container->get(UserRepository::class);
        $postRepository = self::$container->get(PostRepository::class);

        // Login as admin
        $testAdmin = $userRepository->findOneByUsername('admin');
        $client->loginUser($testAdmin, 'default');

        $post = $postRepository->findOneBy(['parent_post' => null]);

        $crawler = $client->request('GET', "/{$post->getBoard()->getName()}/{$post->getSlug()}");
        $client->submit($crawler->filter("form[action='/makesticky/{$post->getId()}']")->form());

        $this->assertSelectorExists("#{$post->getSlug()} .sticky");

        $post = $postRepository->find($post->getId());

        $this->assertTrue($post->getSticky());
    }

    public function testDeletePost()
    {
        $client = static::createClient();

        // Login as admin
        $userRepository = static::$container->get(UserRepository::class);
        $testAdmin = $userRepository->findOneByUsername('admin');
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

    public function testShowPost404()
    {
        $client = static::createClient();

        $client->request('GET', '/boardnamedoesntexist/postslug');

        $this->assertTrue($client->getResponse()->isNotFound());
    }
}
