<?php

namespace App\Tests\Controller\Secure\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AdminUserControllerTest extends WebTestCase
{
    public function testUserIndex()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);

        $testAdmin = $userRepository->findOneByUsername('admin');
        $client->loginUser($testAdmin, 'default');

        $client->request('GET', '/admin/users');

        $this->assertResponseIsSuccessful();
    }
}
