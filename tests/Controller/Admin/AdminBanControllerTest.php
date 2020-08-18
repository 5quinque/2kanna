<?php

namespace App\Tests\Controller\Admin;

use App\Repository\AdminRepository;
use App\Repository\BannedRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AdminBanControllerTest extends WebTestCase
{
    public function testAdminBanIndex()
    {
        $client = static::createClient();
        $adminRepository = static::$container->get(AdminRepository::class);

        $testAdmin = $adminRepository->findOneByUsername('admin');
        $client->loginUser($testAdmin, 'default');


        $client->request('GET', '/admin/ban');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1#admin', 'Admin Dashboard');
    }

    public function testAdminBanForm()
    {
        $client = static::createClient();
        $client->followRedirects();

        $adminRepository = static::$container->get(AdminRepository::class);
        $bannedRepository = static::$container->get(BannedRepository::class);

        $testAdmin = $adminRepository->findOneByUsername('admin');
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

    public function testAdminUnban()
    {
        $client = static::createClient();
        $client->followRedirects();

        $adminRepository = static::$container->get(AdminRepository::class);
        $bannedRepository = static::$container->get(BannedRepository::class);

        [$bannedEntity] = $bannedRepository->findBy(['ipAddress' => '192.168.0.1']);

        $testAdmin = $adminRepository->findOneByUsername('admin');
        $client->loginUser($testAdmin, 'default');

        $crawler = $client->request('GET', '/admin/ban');
        $client->submit($crawler->filter("form[action='/admin/unban/{$bannedEntity->getId()}']")->form());

        $this->assertSelectorTextContains('.alert-success', '192.168.0.1 is now unbanned');

        $bannedEntity = $bannedRepository->findBy(['ipAddress' => '192.168.0.1']);

        $this->assertEmpty($bannedEntity);
    }
}
