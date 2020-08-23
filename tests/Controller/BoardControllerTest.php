<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use App\Repository\BoardRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BoardControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
    }

    public function testShowBoards()
    {
        $client = static::createClient();

        /** @var \App\Entity\Board $boards */
        $boards = self::$container->get(BoardRepository::class)->findAll();

        foreach ($boards as $board) {
            $url = $client->getContainer()->get('router')->generate(
                'board_show',
                ['name' => $board->getName()]
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
                'post[message]' => 'Test Message',
            ]
        );

        $newPost = $crawler->filter('.post-body .message')->text();

        $this->assertSame('Test Message', $newPost);
    }
}
