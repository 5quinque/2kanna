<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{
    public function testLoginView()
    {
        $client = static::createClient();

        $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('button[type="submit"]', 'Login');
    }

    public function testRegister()
    {
        $client = static::createClient();
        $client->followRedirects();

        $client->request('GET', '/login');

        $client->submitForm(
            'Register',
            [
                'register[username]' => 'test_user',
                'register[plainPassword]' => 'test_password'
            ]
        );

        $this->assertResponseIsSuccessful();

        $newUser = static::$container->get(UserRepository::class)->findOneByUsername('test_user');

        $this->assertNotEmpty($newUser);
    }
}
