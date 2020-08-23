<?php

namespace App\Tests\Controller\Secure;

use App\Repository\UserRepository;
use App\Repository\BoardRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ManageBoardsControllerTest extends WebTestCase
{
    public function testNewBoard(): void
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $boardRepository = static::$container->get(BoardRepository::class);

        // Need to be logged in to create board
        $testAdmin = $userRepository->findOneByUsername('admin');
        $client->loginUser($testAdmin, 'default');

        $client->followRedirects();

        $crawler = $client->request('GET', '/boards');

        $crawler = $client->submitForm(
            'Save',
            [
                'new_board[name]' => 'testboard',
            ]
        );

        $this->assertResponseIsSuccessful();

        $newBoard = $boardRepository->findOneBy(['name' => 'testboard']);
        $this->assertNotEmpty($newBoard);
    }

    public function testDeleteBoard()
    {
        $client = static::createClient();
        $client->followRedirects();

        $userRepository = static::$container->get(UserRepository::class);
        $boardRepository = static::$container->get(BoardRepository::class);

        [$board] = $boardRepository->findAll();

        // Need to be logged in to create board
        $testAdmin = $userRepository->findOneByUsername('admin');
        $client->loginUser($testAdmin, 'default');

        $client->followRedirects();

        $crawler = $client->request('GET', '/boards');

        $client->submit($crawler->filter("form[action='/boards/{$board->getName()}']")->form());

        $this->assertResponseIsSuccessful();

        $this->assertEmpty($boardRepository->find($board->getId()));
    }
}
