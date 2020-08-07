<?php

namespace App\Tests\Controller\Admin;

use App\Repository\AdminRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 * @covers \App\Controller\Admin\AdminController
 */
class AdminControllerTest extends WebTestCase
{
    public function testAdminIndex()
    {
        $client = static::createClient();
        $adminRepository = static::$container->get(AdminRepository::class);

        // retrieve the admin user
        $testAdmin = $adminRepository->findOneByUsername('admin');

        // simulate $testUser being logged in
        $client->loginUser($testAdmin, 'default');

        // test e.g. the profile page
        $client->request('GET', '/admin');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1#admin', 'Admin Dashboard');
    }
}
