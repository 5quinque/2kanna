<?php

namespace App\Tests\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 * @covers \App\Controller\PostController
 */
class PostControllerTest extends WebTestCase
{
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
                'post[title]' => 'Test Child Title!', 'post[message]' => 'Test Child Message',
            ]
        );

        $newPostTitle = $crawler->filter('.post-highlight h5.post-title')->text();

        $this->assertSame('Test Child Title!', $newPostTitle);
    }

    public function testShowPost404()
    {
        $client = static::createClient();

        $client->request('GET', '/boardnamedoesntexist/postslug');

        $this->assertTrue($client->getResponse()->isNotFound());
    }
}
