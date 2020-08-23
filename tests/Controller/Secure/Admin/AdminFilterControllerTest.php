<?php

namespace App\Tests\Controller\Secure\Admin;

use App\Repository\UserRepository;
use App\Repository\WordFilterRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AdminFilterControllerTest extends WebTestCase
{
    public function testFilterIndex()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);

        $testAdmin = $userRepository->findOneByUsername('admin');
        $client->loginUser($testAdmin, 'default');

        $client->request('GET', '/admin/wordfilter');

        $this->assertResponseIsSuccessful();
    }
}
