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

    public function testNewUser()
    {
        $client = static::createClient();
        $client->followRedirects();

        $userRepository = static::$container->get(UserRepository::class);

        $testAdmin = $userRepository->findOneByUsername('admin');
        $client->loginUser($testAdmin, 'default');

        $client->request('GET', '/admin/users');

        $crawler = $client->submitForm(
            'Add User',
            [
                'new_user[username]' => 'test_user',
                'new_user[password]' => 'test_password'
            ]
        );

        $this->assertResponseIsSuccessful();

        $newUser = $userRepository->findOneByUsername('test_user');

        $this->assertNotEmpty($newUser);
    }
}
