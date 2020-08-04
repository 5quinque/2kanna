<?php

namespace App\Tests\Controller;

use App\Repository\BoardRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 * @coversNothing
 */
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
}
