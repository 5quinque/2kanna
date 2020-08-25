<?php

namespace App\Tests\Controller\Secure\Admin;

use App\Entity\Setting;
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

    public function testSettingUpdate()
    {
        $client = static::createClient();
        $client->followRedirects();

        $userRepository = static::$container->get(UserRepository::class);
        $settingRepository = static::$container->get(SettingRepository::class);

        $testAdmin = $userRepository->findOneByUsername('admin');
        $client->loginUser($testAdmin, 'default');

        $crawler = $client->request('GET', '/admin/settings');

        // Update lead text
        $form = $crawler->filter("form[name='setting_leadtext']")->form();
        $client->submit($form, ['setting_leadtext[value]' => 'new_leadtext']);

        $this->assertResponseIsSuccessful();

        $leadText = $settingRepository->findOneBy(['name' => 'leadtext']);

        $this->assertSame('new_leadtext', $leadText->getValue());
    }
}
