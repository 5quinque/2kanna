<?php

namespace App\Tests\Controller\Secure\User;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->followRedirects();

        $userRepository = static::$container->get(UserRepository::class);

        $testUser = $userRepository->findOneByUsername('user');
        $client->loginUser($testUser, 'default');

        $crawler = $client->request('GET', '/user');

        $this->assertResponseIsSuccessful();
    }
}
