<?php

namespace App\Tests\Controller\Secure\Admin;

use App\Repository\UserRepository;
use App\Repository\SettingRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AdminSettingsControllerTest extends WebTestCase
{
    public function testSettingsIndex()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);

        $testAdmin = $userRepository->findOneByUsername('admin');
        $client->loginUser($testAdmin, 'default');

        $client->request('GET', '/admin/settings');

        $this->assertResponseIsSuccessful();
    }
}
