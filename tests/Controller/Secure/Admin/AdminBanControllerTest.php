<?php

namespace App\Tests\Controller\Secure\Admin;

use App\Repository\UserRepository;
use App\Repository\BannedRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AdminBanControllerTest extends WebTestCase
{
    public function testBanIndex()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);

        $testAdmin = $userRepository->findOneByUsername('admin');
        $client->loginUser($testAdmin, 'default');

        $client->request('GET', '/admin/ban');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1#admin', 'Admin Dashboard');
    }

    public function testBanForm()
    {
        $client = static::createClient();
        $client->followRedirects();

        $userRepository = static::$container->get(UserRepository::class);
        $bannedRepository = static::$container->get(BannedRepository::class);

        $testAdmin = $userRepository->findOneByUsername('admin');
        $client->loginUser($testAdmin, 'default');

        $client->request('GET', '/admin/ban');

        $crawler = $client->submitForm(
            'Ban',
            [
                'banned[ipAddress]' => '127.0.0.1',
                'banned[reason]' => 'You are banned'
            ]
        );

        $this->assertSelectorTextContains('.alert-success', '127.0.0.1 is now banned :)');

        $bannedEntity = $bannedRepository->findBy(['ipAddress' => '127.0.0.1']);

        $this->assertNotEmpty($bannedEntity);
    }

    public function testUnban()
    {
        $client = static::createClient();
        $client->followRedirects();

        $userRepository = static::$container->get(UserRepository::class);
        $bannedRepository = static::$container->get(BannedRepository::class);

        [$bannedEntity] = $bannedRepository->findBy(['ipAddress' => '192.168.0.1']);

        $testAdmin = $userRepository->findOneByUsername('admin');
        $client->loginUser($testAdmin, 'default');

        $crawler = $client->request('GET', '/admin/ban');
        $client->submit($crawler->filter("form[action='/admin/unban/{$bannedEntity->getId()}']")->form());

        $this->assertSelectorTextContains('.alert-success', '192.168.0.1 is now unbanned');

        $bannedEntity = $bannedRepository->findBy(['ipAddress' => '192.168.0.1']);

        $this->assertEmpty($bannedEntity);
    }
}
