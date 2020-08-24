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

    public function testNewFilter()
    {
        $client = static::createClient();
        $client->followRedirects();

        $userRepository = static::$container->get(UserRepository::class);

        $testAdmin = $userRepository->findOneByUsername('admin');
        $client->loginUser($testAdmin, 'default');

        $client->request('GET', '/admin/wordfilter');

        $crawler = $client->submitForm(
            'Add to filter',
            [
                'word_filter[badWord]' => '/test/',
            ]
        );

        $this->assertResponseIsSuccessful();

        $newWord = static::$container->get(WordFilterRepository::class)->findOneBy(['badWord' => '/test/']);

        $this->assertNotEmpty($newWord);
    }

    public function testEditFilter()
    {
        $client = static::createClient();
        $client->followRedirects();

        $userRepository = static::$container->get(UserRepository::class);

        $testAdmin = $userRepository->findOneByUsername('admin');
        $client->loginUser($testAdmin, 'default');

        $crawler = $client->request('GET', '/admin/wordfilter');
        $editLink = $crawler->filter('.filter-list a')->link();
        $client->click($editLink);

        $this->assertResponseIsSuccessful();

        $crawler = $client->submitForm(
            'Update',
            [
                'word_filter[badWord]' => '/test_word_edit/',
            ]
        );

        $this->assertResponseIsSuccessful();

        $editedWork = static::$container->get(WordFilterRepository::class)->findOneBy(['badWord' => '/test_word_edit/']);

        $this->assertNotEmpty($editedWork);
    }

    public function testRemoveFilter()
    {
        $client = static::createClient();
        $client->followRedirects();

        $userRepository = static::$container->get(UserRepository::class);
        $wordRepository = static::$container->get(WordFilterRepository::class);

        $testAdmin = $userRepository->findOneByUsername('admin');
        $client->loginUser($testAdmin, 'default');

        $crawler = $client->request('GET', '/admin/wordfilter');

        [$word] = $wordRepository->findAll();

        $removeUrl = "/admin/removefilter/{$word->getId()}";
        $client->submit($crawler->filter("form[action='{$removeUrl}']")->form());

        $this->assertResponseIsSuccessful();

        $this->assertEmpty($wordRepository->find($word->getId()));
    }
}
