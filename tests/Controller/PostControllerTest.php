<?php

namespace App\Tests\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 * @coversNothing
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

    public function testNewPost(): void
    {
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';

        $client = static::createClient();
        $client->followRedirects();

        // Find first board
        $crawler = $client->request('GET', '/');
        $boardLink = $crawler->filter('ul.boards-list a')->link();

        $client->click($boardLink);
        $crawler = $client->submitForm(
            'New Post',
            [
                'post[title]' => 'Test Title!', 'post[message]' => 'Test Message',
            ]
        );

        $newPostTitle = $crawler->filter('h5.post-title')->text();

        $this->assertSame('Test Title!', $newPostTitle);
    }
}
